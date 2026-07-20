<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Client\ClientsModel;
use App\Models\Client\TransactionsModel;
use Config\Database;

class HistoriqueController extends BaseController
{
    protected $db;
    protected ClientsModel $clientsModel;
    protected TransactionsModel $transactionsModel;

    public function __construct()
    {
        $this->db               = Database::connect();
        $this->clientsModel      = new ClientsModel();
        $this->transactionsModel = new TransactionsModel();
    }

    public function showHistorique()
    {
        $clientId = session()->get('client_id');
        $client   = $this->clientsModel->find($clientId);

        $typesOperation = $this->db->table('types_operation')->get()->getResultArray();

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
