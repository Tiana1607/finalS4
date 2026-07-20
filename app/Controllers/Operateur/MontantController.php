<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\Operateur\TransactionModel;

class MontantController extends BaseController
{
    protected TransactionModel $transactionModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        $montants = $this->transactionModel->getMontantsParOperateur();

        $totalGeneral = 0;
        foreach ($montants as $m) {
            $totalGeneral += (float) $m['montant_total'];
        }

        $data = [
            'montants'     => $montants,
            'totalGeneral' => $totalGeneral,
        ];

        return view('admin/montants_reverser', $data);
    }
}
