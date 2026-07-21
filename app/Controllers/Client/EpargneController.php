<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Client\ClientsModel;
use App\Models\Client\EpargneModel;
use CodeIgniter\HTTP\ResponseInterface;

class EpargneController extends BaseController
{
    protected ClientsModel $clientsModel;
    protected EpargneModel $epargneModel;

    public function __construct()
    {
        $this->clientsModel = new ClientsModel();
        $this->epargneModel = new EpargneModel();
    }
    public function index()
    {
        $client = $this->clientsModel->find(session()->get('client_id'));
        $epargne = $this->epargneModel->getEpargneByClient(session()->get('client_id'));

        return view('client/epargne', ['client' => $client, 'solde' => $epargne]);
    }


    public function getSoldes()
    {
        $montant = $this->request->getPost('pourcentage');
        $clientId = session()->get('client_id');
        $soldeActuel = 0;
        $epargne = 0;

        if(empty($montant) || !is_numeric($montant) || $montant <= 0) {
            return redirect()->back()->withInput()->with('error', 'Veuillez saisir un pourcentage valide ou annuler.');
        }

        $client_epargne = $this->epargneModel->InsertEpargne($clientId, $montant);

        $message = 'Epargne insérée effectué avec succès.';

        $client = $this->clientsModel->find(session()->get('client_id'));

        if ($client_epargne['pourcentage'] != 0 || $client_epargne['pourcentage'] != null) {
            $soldeActuel = $client['solde'] - ($client['solde'] * ($client_epargne['pourcentage'] / 100));
            $epargne = $client['solde'] * ($client_epargne['pourcentage'] / 100);
        }

        return redirect()->to('/client/epargne')->with('success', $message);
    }

}
