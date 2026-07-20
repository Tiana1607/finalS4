<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Client\ClientsModel;
use App\Models\Client\TransactionsModel;
use App\Models\TrancheMontantModel;

class ClientsController extends BaseController
{
    protected ClientsModel $clientsModel;
    protected TransactionsModel $transactionsModel;
    protected TrancheMontantModel $trancheModel;

    // IDs des types d'opération (voir TypesOperationSeeder)
    protected int $typeOperationDepot    = 1;
    protected int $typeOperationRetrait  = 2;
    protected int $typeOperationTransfert = 3;

    public function __construct()
    {
        $this->clientsModel      = new ClientsModel();
        $this->transactionsModel = new TransactionsModel();
        $this->trancheModel      = new TrancheMontantModel();
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
}
