<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\Operateur\ClientModel;
use App\Models\Operateur\TransactionModel;
use App\Models\PrefixesModel;
class ClientController extends BaseController
{
    protected ClientModel $clientModel;
    protected TransactionModel $transactionModel;

    protected PrefixesModel $prefixesModel;

    public function __construct()
    {
        $this->clientModel      = new ClientModel();
        $this->transactionModel = new TransactionModel();
        $this->prefixesModel    = new PrefixesModel();
    }

    public function index()
    {
       $tousLesClients = $this->clientModel->getAllWithStats();

       $nosClient = [];

       foreach ($tousLesClients as $client) {
           if ($this->prefixesModel->prefixeEstANous($client['telephone'])) {
               $nosClient[] = $client;
           }
       }


       $data = [
           'clients' => $nosClient,
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
