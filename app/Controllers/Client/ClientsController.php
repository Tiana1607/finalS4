<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Client\ClientsModel;
use App\Models\Client\TransactionsModel;
use App\Models\TrancheMontantModel;
use App\Models\PrefixesModel;
use \Config\Database;

class ClientsController extends BaseController
{
    protected $db;
    protected ClientsModel $clientsModel;
    protected TransactionsModel $transactionsModel;
    protected TrancheMontantModel $trancheModel;
    protected PrefixesModel $prefixesModel;

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
        $client = $this->clientsModel->find(session()->get('client_id'));

        return view('client/transfert', ['client' => $client]);
    }

    public function transfert()
    {
        $montant         = $this->request->getPost('montant');
        $destinataireTel = $this->request->getPost('destinataire');
        $avecFraisRetrait = (int) $this->request->getPost('frais_retrait');
        $clientId        = session()->get('client_id');

        // Validation : montant requis, numérique, supérieur à 0
        if (empty($montant) || !is_numeric($montant) || $montant <= 0) {
            return redirect()->back()->withInput()->with('error', 'Veuillez saisir un montant valide supérieur à 0.');
        }

        $montant = $montant;

        // Validation : destinataire requis
        if (empty(trim($destinataireTel))) {
            return redirect()->back()->withInput()->with('error', 'Veuillez saisir le numéro du destinataire.');
        }

        // Supprimer les espaces et vérifier que le numéro fait exactement 10 chiffres
        $destinataireTel = preg_replace('/\s+/', '', trim($destinataireTel));
        if (!preg_match('/^\d{10}$/', $destinataireTel)) {
            return redirect()->back()->withInput()->with('error', 'Le numéro du destinataire doit contenir exactement 10 chiffres (ex : 032 12 123 12).');
        }

        // Vérifier que le destinataire existe
        $destinataire = $this->clientsModel->findByTelephone($destinataireTel);

        if ($destinataire === null) {
            return redirect()->back()->withInput()->with('error', 'Aucun client trouvé avec le numéro ' . esc(trim($destinataireTel)) . '.');
        }

        // Vérifier qu'on ne se transfère pas à soi-même
        if ((int) $destinataire['id'] === $clientId) {
            return redirect()->back()->withInput()->with('error', 'Vous ne pouvez pas vous transférer à vous-même.');
        }

        // Calcul des frais de transfert via le barème opérateur
        $fraisTransfert = $this->trancheModel->getFrais($this->typeOperationTransfert, $montant);

        // Calcul des frais de retrait si l'émetteur choisit de les payer
        $fraisRetrait = 0;
        if ($avecFraisRetrait === 1) {
            $fraisRetrait = $this->trancheModel->getFrais($this->typeOperationRetrait, $montant);
        }

        $totalFrais = $fraisTransfert + $fraisRetrait;

        // Récupérer le solde de l'émetteur
        $emetteur = $this->clientsModel->find($clientId);
        $solde    = $emetteur['solde'];

        // Vérifier que le solde est suffisant
        $coutTotal = $montant + $totalFrais;
        if ($solde < $coutTotal) {
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant. Solde actuel : '
                . number_format($solde, 0, ',', ' ') . ' Ar — Coût total : '
                . number_format($coutTotal, 0, ',', ' ') . ' Ar.');
        }

        // Débiter l'émetteur
        $nouveauSoldeEmetteur = $solde - $montant - $totalFrais;
        $this->clientsModel->update($clientId, ['solde' => $nouveauSoldeEmetteur]);

        // Créditer le destinataire
        $creditDestinataire = $montant + $fraisRetrait;
        $nouveauSoldeDestinataire = $destinataire['solde'] + $creditDestinataire;
        $this->clientsModel->update($destinataire['id'], ['solde' => $nouveauSoldeDestinataire]);

        // Enregistrer la transaction
        $this->transactionsModel->insert([
            'client_id'         => $clientId,
            'destinataire_id'   => $destinataire['id'],
            'type_operation_id' => $this->typeOperationTransfert,
            'montant'           => $montant,
            'frais'             => $totalFrais,
        ]);

        $message = 'Transfert de ' . number_format($montant, 0, ',', ' ') . ' Ar vers ' . esc(formaterTelephone($destinataire['telephone'])) . ' effectué avec succès.';
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
        if ($this->request->getHeader('X-Requested-With') === 'XMLHttpRequest') {
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
