<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\Operateur\CommissionModel;
use App\Models\OperateurModel;

class CommissionController extends BaseController
{
    protected CommissionModel $commissionModel;
    protected OperateurModel $operateurModel;

    public function __construct()
    {
        $this->commissionModel = new CommissionModel();
        $this->operateurModel  = new OperateurModel();
    }

    public function index()
    {
        $operateurs   = $this->operateurModel->getOperateursExterne();
        $commissions  = $this->commissionModel->getAllWithOperateur();

        $data = [
            'operateurs'  => $operateurs,
            'commissions' => $commissions,
        ];

        return view('admin/commissions', $data);
    }

    public function popup(int $id)
    {
        $commission = $this->commissionModel->getByOperateur($id);

        if (! $commission) {
            $operateur = $this->operateurModel->find($id);
            if (! $operateur) {
                return redirect()->to('/admin/commissions')->with('error', 'Opérateur introuvable.');
            }
            $commission = [
                'operateur_id' => $id,
                'pourcentage'  => 0,
                'operateur_nom' => $operateur['nom'],
            ];
        }

        return view('admin/popup/operateur_commission_popup', [
            'commission' => $commission,
        ]);
    }

    public function update(int $id)
    {
        $rules = [
            'pourcentage' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $pourcentage = (float) $this->request->getPost('pourcentage');
        $operateurId = $id;

        $existing = $this->commissionModel->getByOperateur($operateurId);

        if ($existing) {
            $this->commissionModel->update($existing['id'], [
                'pourcentage' => $pourcentage,
            ]);
        } else {
            $this->commissionModel->insert([
                'operateur_id' => $operateurId,
                'pourcentage'  => $pourcentage,
            ]);
        }

        return redirect()->to('/admin/commissions')
                         ->with('success', 'Commission mise à jour avec succès.');
    }

    public function store()
    {
        $rules = [
            'operateur_id' => 'required|integer',
            'pourcentage'  => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $operateurId = (int) $this->request->getPost('operateur_id');
        $pourcentage = (float) $this->request->getPost('pourcentage');

        $existing = $this->commissionModel->getByOperateur($operateurId);

        if ($existing) {
            $this->commissionModel->update($existing['id'], [
                'pourcentage' => $pourcentage,
            ]);
        } else {
            $this->commissionModel->insert([
                'operateur_id' => $operateurId,
                'pourcentage'  => $pourcentage,
            ]);
        }

        return redirect()->to('/admin/commissions')
                         ->with('success', 'Commission enregistrée avec succès.');
    }
}
