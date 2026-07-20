<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Client\ClientsModel;
use App\Models\Client\TransactionsModel;
use App\Models\TrancheMontantModel;
use App\Models\PrefixesModel;
use App\Models\OperateurModel;
use App\Models\TransfertsGroupeModel;
use \Config\Database;

class ClientsController extends BaseController
{
    protected $db;
    protected ClientsModel $clientsModel;
    protected TransactionsModel $transactionsModel;
    protected TrancheMontantModel $trancheModel;
    protected PrefixesModel $prefixesModel;
    protected OperateurModel $operateurModel;
    protected TransfertsGroupeModel $groupeModel;

    // IDs des types d'opération (voir TypesOperationSeeder)
    protected int $typeOperationDepot    = 1;
    protected int $typeOperationRetrait  = 2;
    protected int $typeOperationTransfert = 3;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->clientsModel      = new ClientsModel();
        $this->transactionsModel = new TransactionsModel();
        $this->trancheModel      = new TrancheMontantModel();
        $this->prefixesModel     = new PrefixesModel();
        $this->operateurModel    = new OperateurModel();
        $this->groupeModel       = new TransfertsGroupeModel();
    }

    // ──────────────────────── Authentification ────────────────────────

    public function showLoginForm()
    {
        return view('client/login');
    }

    public function login()
    {
        $telephone = $this->request->getPost('tel');

        if (empty(trim($telephone))) {
            return redirect()->back()->withInput()->with('error', 'Veuillez saisir votre numéro de téléphone.');
        }

        // Supprimer les espaces et vérifier que le numéro fait exactement 10 chiffres
        $telephone = preg_replace('/\s+/', '', trim($telephone));
        if (!preg_match('/^\d{10}$/', $telephone)) {
            return redirect()->back()->withInput()->with('error', 'Le numéro doit contenir exactement 10 chiffres (ex : 032 12 123 12).');
        }

        // Vérifier que le préfixe du numéro est valide
        if (!$this->prefixesModel->prefixeExiste($telephone)) {
            return redirect()->back()->withInput()->with('error', 'Le préfixe de votre numéro n\'est pas autorisé. Préfixes acceptés : 033, 037.');
        }

        $client = $this->clientsModel->findOrCreateByTelephone($telephone);

        session()->set([
            'client_id' => $client['id'],
            'telephone' => $client['telephone'],
            'logged_in' => true,
        ]);

        return redirect()->to('/client/dashboard');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/');
    }

    public function dashboard()
    {
        $client = $this->clientsModel->find(session()->get('client_id'));

        return view('client/dashboard', ['client' => $client]);
    }

    // ──────────────────────── Dépôt ────────────────────────

    public function showDepotForm()
    {
        $client = $this->clientsModel->find(session()->get('client_id'));

        return view('client/depot', ['client' => $client]);
    }

    public function depot()
    {
        $montant = $this->request->getPost('montant');
        $clientId = session()->get('client_id');

        // Validation : montant requis, numérique, supérieur à 0
        if (empty($montant) || !is_numeric($montant) || $montant <= 0) {
            return redirect()->back()->withInput()->with('error', 'Veuillez saisir un montant valide supérieur à 0.');
        }

        $montant = $montant;

        // Calcul des frais via le barème opérateur
        $frais = $this->trancheModel->getFrais($this->typeOperationDepot, $montant);

        // Récupérer le solde actuel
        $client = $this->clientsModel->find($clientId);
        $solde  = $client['solde'];

        // Si le solde couvre les frais → frais payés depuis le solde, montant credited intégralement
        // Si le solde ne couvre pas les frais → le manque est déduit du dépôt
        $nouveauSolde = $solde + $montant - $frais;

        $this->clientsModel->update($clientId, ['solde' => $nouveauSolde]);

        // Enregistrer la transaction
        $this->transactionsModel->insert([
            'client_id'         => $clientId,
            'destinataire_id'   => null,
            'type_operation_id' => $this->typeOperationDepot,
            'montant'           => $montant,
            'frais'             => $frais,
        ]);

        $message = 'Dépôt de ' . number_format($montant, 0, ',', ' ') . ' Ar effectué avec succès.';
        if ($frais > 0) {
            $message .= ' Frais : ' . number_format($frais, 0, ',', ' ') . ' Ar.';
        }

        return redirect()->to('/client/depot')->with('success', $message);
    }

    // ──────────────────────── Retrait ────────────────────────

    public function showRetraitForm()
    {
        $client = $this->clientsModel->find(session()->get('client_id'));

        return view('client/retrait', ['client' => $client]);
    }

    public function retrait()
    {
        $montant = $this->request->getPost('montant');
        $clientId = session()->get('client_id');

        // Validation : montant requis, numérique, supérieur à 0
        if (empty($montant) || !is_numeric($montant) || $montant <= 0) {
            return redirect()->back()->withInput()->with('error', 'Veuillez saisir un montant valide supérieur à 0.');
        }

        $montant = $montant;

        // Calcul des frais via le barème opérateur
        $frais = $this->trancheModel->getFrais($this->typeOperationRetrait, $montant);

        // Récupérer le solde actuel
        $client = $this->clientsModel->find($clientId);
        $solde  = $client['solde'];

        // Vérifier que le solde est suffisant (montant + frais)
        if ($solde < $montant + $frais) {
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant. Solde actuel : '
                . number_format($solde, 0, ',', ' ') . ' Ar — Montant + frais : '
                . number_format($montant + $frais, 0, ',', ' ') . ' Ar.');
        }

        // Débiter le solde
        $nouveauSolde = $solde - $montant - $frais;
        $this->clientsModel->update($clientId, ['solde' => $nouveauSolde]);

        // Enregistrer la transaction
        $this->transactionsModel->insert([
            'client_id'         => $clientId,
            'destinataire_id'   => null,
            'type_operation_id' => $this->typeOperationRetrait,
            'montant'           => $montant,
            'frais'             => $frais,
        ]);

        $message = 'Retrait de ' . number_format($montant, 0, ',', ' ') . ' Ar effectué avec succès.';
        if ($frais > 0) {
            $message .= ' Frais : ' . number_format($frais, 0, ',', ' ') . ' Ar.';
        }

        return redirect()->to('/client/retrait')->with('success', $message);
    }

    // ──────────────────────── Transfert ────────────────────────

    public function showTransfertForm()
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

        // Validation montant
        if (empty($montant) || !is_numeric($montant) || $montant <= 0) {
            return redirect()->back()->withInput()->with('error', 'Veuillez saisir un montant valide supérieur à 0.');
        }
        $montant = (float) $montant;

        // Filtrer les numéros vides
        $destinataireTels = array_filter($destinataireTels, fn($t) => !empty(trim($t)));
        $destinataireTels = array_values($destinataireTels);

        if (empty($destinataireTels)) {
            return redirect()->back()->withInput()->with('error', 'Veuillez saisir au moins un numéro de destinataire.');
        }

        $nbDestinataires = count($destinataireTels);
        $estMulti        = $nbDestinataires > 1;

        // Valider chaque destinataire
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

        // Calcul des frais
        $montantParDest = $montant;
        $totalFrais     = 0;
        $totalCredit    = 0;

        if ($estMulti) {
            $montantParDest = $montant / $nbDestinataires;
        }

        foreach ($destinatairesValides as $d) {
            $fraisTransfert = $this->trancheModel->getFrais($this->typeOperationTransfert, $montantParDest);
            $fraisRetrait   = 0;

            if ($d['est_nous'] && $avecFraisRetrait === 1) {
                $fraisRetrait = $this->trancheModel->getFrais($this->typeOperationRetrait, $montantParDest);
            }

            $totalFrais  += $fraisTransfert + $fraisRetrait;
            $totalCredit += $montantParDest + $fraisRetrait;
        }

        // Vérifier solde
        $solde = $emetteur['solde'];
        $coutTotal = $montant + $totalFrais;
        if ($solde < $coutTotal) {
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant. Solde actuel : '
                . number_format($solde, 0, ',', ' ') . ' Ar — Coût total : '
                . number_format($coutTotal, 0, ',', ' ') . ' Ar.');
        }

        // Débiter l'émetteur
        $this->clientsModel->update($clientId, ['solde' => $solde - $coutTotal]);

        // Créer le groupe si multi
        $groupeId = null;
        if ($estMulti) {
            $groupeId = $this->groupeModel->insert([
                'client_id'        => $clientId,
                'montant_total'    => $montant,
                'nb_destinataires' => $nbDestinataires,
            ]);
        }

        // Traiter chaque destinataire
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

    // ──────────────────────── Historique ────────────────────────

    public function showHistorique()
    {
        $clientId = session()->get('client_id');
        $client   = $this->clientsModel->find($clientId);

        // Récupérer les types d'opération pour le filtre
        $typesOperation = $this->db->table('types_operation')->get()->getResultArray();

        // Récupérer l'historique complet
        $historique = $this->transactionsModel->getHistorique($clientId);

        return view('client/historique', [
            'client'         => $client,
            'historique'     => $historique,
            'typesOperation' => $typesOperation,
            'filtres'        => [],
            'triDate'        => 'DESC',
        ]);
    }

    public function historique()
    {
        $clientId = session()->get('client_id');

        // Si requête AJAX → retourner du JSON
        if ($this->request->header('X-Requested-With')->getValue() === 'XMLHttpRequest') {
            $filtres = [
                'type_operation' => $this->request->getGet('type_operation'),
                'date_debut'     => $this->request->getGet('date_debut'),
                'date_fin'       => $this->request->getGet('date_fin'),
                'montant_min'    => $this->request->getGet('montant_min'),
                'montant_max'    => $this->request->getGet('montant_max'),
            ];
            $triDate = $this->request->getGet('tri_date') ?: 'DESC';
            $historique = $this->transactionsModel->getHistoriqueFiltre($clientId, $filtres, $triDate);

            return $this->response->setJSON(['historique' => $historique]);
        }

        // Sinon → formulaire classique
        $client = $this->clientsModel->find($clientId);

        $filtres = [
            'type_operation' => $this->request->getPost('type_operation'),
            'date_debut'     => $this->request->getPost('date_debut'),
            'date_fin'       => $this->request->getPost('date_fin'),
            'montant_min'    => $this->request->getPost('montant_min'),
            'montant_max'    => $this->request->getPost('montant_max'),
        ];

        $triDate = $this->request->getPost('tri_date') ?: 'DESC';

        $typesOperation = $this->db->table('types_operation')->get()->getResultArray();
        $historique     = $this->transactionsModel->getHistoriqueFiltre($clientId, $filtres, $triDate);

        return view('client/historique', [
            'client'         => $client,
            'historique'     => $historique,
            'typesOperation' => $typesOperation,
            'filtres'        => $filtres,
            'triDate'        => $triDate,
        ]);
    }
}
