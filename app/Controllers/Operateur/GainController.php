<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\Operateur\TransactionModel;

class GainController extends BaseController
{
    protected TransactionModel $transactionModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        $gainsParType = $this->transactionModel->getGainsParType();
        $retraits     = $this->transactionModel->getTransactionsByType(2);
        $transferts   = $this->transactionModel->getTransactionsByType(3);

        $gainsInternes = $this->transactionModel->getGainsInternes();
        $gainsExternes = $this->transactionModel->getGainsExternes();

        $totalRetrait   = 0;
        $totalTransfert = 0;
        $nbRetrait      = 0;
        $nbTransfert    = 0;

        foreach ($gainsParType as $g) {
            if ($g['type_operation_id'] == 2) {
                $totalRetrait = (float) $g['total_frais'];
                $nbRetrait    = (int) $g['nombre'];
            } elseif ($g['type_operation_id'] == 3) {
                $totalTransfert = (float) $g['total_frais'];
                $nbTransfert    = (int) $g['nombre'];
            }
        }

        $totalInterne = 0;
        $totalExterne = 0;

        foreach ($gainsInternes as $gi) {
            $totalInterne += (float) $gi['total_frais'];
        }

        foreach ($gainsExternes as $ge) {
            $totalExterne += (float) $ge['total_commission'];
        }

        $data = [
            'totalRetrait'   => $totalRetrait,
            'totalTransfert' => $totalTransfert,
            'totalGeneral'   => $totalRetrait + $totalTransfert,
            'nbRetrait'      => $nbRetrait,
            'nbTransfert'    => $nbTransfert,
            'retraits'       => $retraits,
            'transferts'     => $transferts,
            'gainsInternes'  => $gainsInternes,
            'gainsExternes'  => $gainsExternes,
            'totalInterne'   => $totalInterne,
            'totalExterne'   => $totalExterne,
        ];

        return view('admin/gains', $data);
    }
}
