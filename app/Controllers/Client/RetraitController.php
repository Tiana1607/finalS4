<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Client\ClientsModel;
use App\Models\Client\TransactionsModel;
use App\Models\TrancheMontantModel;

class RetraitController extends BaseController
{
    protected ClientsModel $clientsModel;
    protected TransactionsModel $transactionsModel;
    protected TrancheMontantModel $trancheModel;

    protected int $typeOperationRetrait = 2;

    public function __construct()
    {
        $this->clientsModel      = new ClientsModel();
        $this->transactionsModel = new TransactionsModel();
        $this->trancheModel      = new TrancheMontantModel();
    }

    public function showForm()
    {
        $client = $this->clientsModel->find(session()->get('client_id'));

        return view('client/retrait', ['client' => $client]);
    }

    public function retrait()
    {
        $montant = $this->request->getPost('montant');
        $clientId = session()->get('client_id');

        if (empty($montant) || !is_numeric($montant) || $montant <= 0) {
            return redirect()->back()->withInput()->with('error', 'Veuillez saisir un montant valide supérieur à 0.');
        }

        $montant = $montant;

        $frais = $this->trancheModel->getFrais($this->typeOperationRetrait, $montant);

        $client = $this->clientsModel->find($clientId);
        $solde  = $client['solde'];

        if ($solde < $montant + $frais) {
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant. Solde actuel : '
                . number_format($solde, 0, ',', ' ') . ' Ar — Montant + frais : '
                . number_format($montant + $frais, 0, ',', ' ') . ' Ar.');
        }

        $nouveauSolde = $solde - $montant - $frais;
        $this->clientsModel->update($clientId, ['solde' => $nouveauSolde]);

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
}
