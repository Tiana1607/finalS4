<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Client\ClientsModel;
use App\Models\Client\TransactionsModel;
use App\Models\TrancheMontantModel;
use App\Models\PrefixesModel;
use App\Models\OperateurModel;
use App\Models\TransfertsGroupeModel;

class TransfertController extends BaseController
{
    protected ClientsModel $clientsModel;
    protected TransactionsModel $transactionsModel;
    protected TrancheMontantModel $trancheModel;
    protected PrefixesModel $prefixesModel;
    protected OperateurModel $operateurModel;
    protected TransfertsGroupeModel $groupeModel;

    protected int $typeOperationRetrait  = 2;
    protected int $typeOperationTransfert = 3;

    public function __construct()
    {
        $this->clientsModel      = new ClientsModel();
        $this->transactionsModel = new TransactionsModel();
        $this->trancheModel      = new TrancheMontantModel();
        $this->prefixesModel     = new PrefixesModel();
        $this->operateurModel    = new OperateurModel();
        $this->groupeModel       = new TransfertsGroupeModel();
    }

    public function showForm()
    {
        $client  = $this->clientsModel->find(session()->get('client_id'));
        $emetteurOperateurId = $this->prefixesModel->getOperateurIdByTelephone($client['telephone']);

        return view('client/transfert', [
            'client'  => $client,
            'emetteurOperateurId' => $emetteurOperateurId,
        ]);
    }

    public function detecterOperateur()
    {
        $telephone = preg_replace('/\s+/', '', trim($this->request->getGet('tel') ?? ''));
        $clientId  = session()->get('client_id');

        if (strlen($telephone) < 3) {
            return $this->response->setJSON(['operateur_id' => null, 'est_nous' => false, 'nom' => '']);
        }

        $operateurId = $this->prefixesModel->getOperateurIdByTelephone($telephone);
        $emetteur    = $this->clientsModel->find($clientId);
        $emetteurOp  = $this->prefixesModel->getOperateurIdByTelephone($emetteur['telephone']);

        $estNous = ($operateurId !== null && $emetteurOp !== null && $operateurId === $emetteurOp);

        $nom = '';
        if ($operateurId !== null) {
            $op = $this->operateurModel->find($operateurId);
            $nom = $op ? $op['nom'] : '';
        }

        return $this->response->setJSON([
            'operateur_id' => $operateurId,
            'est_nous'     => $estNous,
            'nom'          => $nom,
        ]);
    }

    public function transfert()
    {
        $montant         = $this->request->getPost('montant');
        $destinataireTels = $this->request->getPost('destinataires') ?? [];
        $avecFraisRetrait = (int) $this->request->getPost('frais_retrait');
        $clientId        = session()->get('client_id');

        if (empty($montant) || !is_numeric($montant) || $montant <= 0) {
            return redirect()->back()->withInput()->with('error', 'Veuillez saisir un montant valide supérieur à 0.');
        }
        $montant = (float) $montant;

        $destinataireTels = array_filter($destinataireTels, fn($t) => !empty(trim($t)));
        $destinataireTels = array_values($destinataireTels);

        if (empty($destinataireTels)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez saisir au moins un numéro de destinataire.');
        }

        $nbDestinataires = count($destinataireTels);
        $estMulti        = $nbDestinataires > 1;

        $destinatairesValides = [];
        $emetteur = $this->clientsModel->find($clientId);
        $emetteurOperateurId = $this->prefixesModel->getOperateurIdByTelephone($emetteur['telephone']);
        $tousMemeOperateur   = true;

        foreach ($destinataireTels as $tel) {
            $tel = preg_replace('/\s+/', '', trim($tel));

            if (!preg_match('/^\d{10}$/', $tel)) {
                return redirect()->back()->withInput()->with('error', 'Chaque numéro doit contenir exactement 10 chiffres (ex : 032 12 123 12).');
            }

            $destinataire = $this->clientsModel->findByTelephone($tel);

            if ($destinataire === null) {
                return redirect()->back()->withInput()->with('error', 'Aucun client trouvé avec le numéro ' . esc(formaterTelephone($tel)) . '.');
            }

            if ((int) $destinataire['id'] === $clientId) {
                return redirect()->back()->withInput()->with('error', 'Vous ne pouvez pas vous transférer à vous-même (' . esc(formaterTelephone($tel)) . ').');
            }

            $destOperateurId = $this->prefixesModel->getOperateurIdByTelephone($tel);
            if ($destOperateurId !== $emetteurOperateurId) {
                $tousMemeOperateur = false;
            }

            $destinatairesValides[] = [
                'client'           => $destinataire,
                'operateur_id'     => $destOperateurId,
                'est_nous'         => ($destOperateurId === $emetteurOperateurId),
            ];
        }

        $montantParDest = $montant;
        $totalFrais     = 0;

        if ($estMulti) {
            $montantParDest = $montant / $nbDestinataires;
        }

        foreach ($destinatairesValides as $d) {
            $fraisTransfert = $this->trancheModel->getFrais($this->typeOperationTransfert, $montantParDest);
            $fraisRetrait   = 0;

            if ($d['est_nous'] && $avecFraisRetrait === 1) {
                $fraisRetrait = $this->trancheModel->getFrais($this->typeOperationRetrait, $montantParDest);
            }

            $totalFrais += $fraisTransfert + $fraisRetrait;
        }

        $solde = $emetteur['solde'];
        $coutTotal = $montant + $totalFrais;
        if ($solde < $coutTotal) {
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant. Solde actuel : '
                . number_format($solde, 0, ',', ' ') . ' Ar — Coût total : '
                . number_format($coutTotal, 0, ',', ' ') . ' Ar.');
        }

        $this->clientsModel->update($clientId, ['solde' => $solde - $coutTotal]);

        $groupeId = null;
        if ($estMulti) {
            $groupeId = $this->groupeModel->insert([
                'client_id'        => $clientId,
                'montant_total'    => $montant,
                'nb_destinataires' => $nbDestinataires,
            ]);
        }

        $noms = [];
        foreach ($destinatairesValides as $d) {
            $fraisTransfert = $this->trancheModel->getFrais($this->typeOperationTransfert, $montantParDest);
            $fraisRetrait   = 0;

            if ($d['est_nous'] && $avecFraisRetrait === 1) {
                $fraisRetrait = $this->trancheModel->getFrais($this->typeOperationRetrait, $montantParDest);
            }

            $totalFraisDest = $fraisTransfert + $fraisRetrait;
            $creditDest     = $montantParDest + $fraisRetrait;

            $this->clientsModel->update($d['client']['id'], [
                'solde' => $d['client']['solde'] + $creditDest,
            ]);

            $this->transactionsModel->insert([
                'client_id'                   => $clientId,
                'destinataire_id'             => $d['client']['id'],
                'type_operation_id'           => $this->typeOperationTransfert,
                'montant'                     => $montantParDest,
                'frais'                       => $totalFraisDest,
                'operateur_destinataire_id'   => $d['operateur_id'],
                'groupe_id'                   => $groupeId,
            ]);

            $noms[] = formaterTelephone($d['client']['telephone']);
        }

        $liste = implode(', ', $noms);
        $message = 'Transfert de ' . number_format($montant, 0, ',', ' ') . ' Ar vers ' . $liste . ' effectué avec succès.';
        if ($totalFrais > 0) {
            $message .= ' Frais : ' . number_format($totalFrais, 0, ',', ' ') . ' Ar.';
        }

        return redirect()->to('/client/transfert')->with('success', $message);
    }
}
