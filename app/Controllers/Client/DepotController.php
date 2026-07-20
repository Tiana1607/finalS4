<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Client\ClientsModel;
use App\Models\Client\TransactionsModel;
use App\Models\TrancheMontantModel;

class DepotController extends BaseController
{
    protected ClientsModel $clientsModel;
    protected TransactionsModel $transactionsModel;
    protected TrancheMontantModel $trancheModel;

    protected int $typeOperationDepot = 1;

    public function __construct()
    {
        $this->clientsModel      = new ClientsModel();
        $this->transactionsModel = new TransactionsModel();
        $this->trancheModel      = new TrancheMontantModel();
    }

    public function showForm()
    {
        $client = $this->clientsModel->find(session()->get('client_id'));

        return view('client/depot', ['client' => $client]);
    }

    public function depot()
    {
        $montant = $this->request->getPost('montant');
        $clientId = session()->get('client_id');

        if (empty($montant) || !is_numeric($montant) || $montant <= 0) {
            return redirect()->back()->withInput()->with('error', 'Veuillez saisir un montant valide supérieur à 0.');
        }

        $montant = $montant;

        $frais = $this->trancheModel->getFrais($this->typeOperationDepot, $montant);

        $client = $this->clientsModel->find($clientId);
        $solde  = $client['solde'];

        $nouveauSolde = $solde + $montant - $frais;

        $this->clientsModel->update($clientId, ['solde' => $nouveauSolde]);

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
}
