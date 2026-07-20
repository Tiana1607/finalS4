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

        $data = [
            'totalRetrait'   => $totalRetrait,
            'totalTransfert' => $totalTransfert,
            'totalGeneral'   => $totalRetrait + $totalTransfert,
            'nbRetrait'      => $nbRetrait,
            'nbTransfert'    => $nbTransfert,
            'retraits'       => $retraits,
            'transferts'     => $transferts,
        ];

        return view('admin/gains', $data);
    }
}
