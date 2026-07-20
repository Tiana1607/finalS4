<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\Operateur\ClientModel;
use App\Models\Operateur\TransactionModel;
use App\Models\Operateur\PrefixeModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $clientModel      = new ClientModel();
        $transactionModel = new TransactionModel();
        $prefixeModel     = new PrefixeModel();

        $nbClients       = $clientModel->countAllResults(false);
        $nbTransactions  = $transactionModel->countAllResults(false);
        $nbPrefixes      = $prefixeModel->countAllResults(false);

        $gainsParType = $transactionModel->getGainsParType();
        $totalGains   = 0;
        foreach ($gainsParType as $g) {
            $totalGains += (float) $g['total_frais'];
        }

        $data = [
            'nbClients'      => $nbClients,
            'nbTransactions' => $nbTransactions,
            'nbPrefixes'     => $nbPrefixes,
            'totalGains'     => $totalGains,
        ];

        return view('admin/dashboard', $data);
    }
}
