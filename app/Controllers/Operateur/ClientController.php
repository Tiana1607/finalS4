<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\Operateur\ClientModel;
use App\Models\Operateur\TransactionModel;

class ClientController extends BaseController
{
    protected ClientModel $clientModel;
    protected TransactionModel $transactionModel;

    public function __construct()
    {
        $this->clientModel      = new ClientModel();
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        $data = [
            'clients' => $this->clientModel->getAllWithStats(),
        ];

        return view('admin/clients', $data);
    }

    public function detail(int $id)
    {
        $client = $this->clientModel->find($id);

        if (! $client) {
            return redirect()->to('/admin/clients')->with('error', 'Client introuvable.');
        }

        $transactions = $this->transactionModel->getTransactionsByClient($id);

        $data = [
            'client'       => $client,
            'transactions' => $transactions,
        ];

        return view('admin/popup/client_detail_popup', $data);
    }
}
