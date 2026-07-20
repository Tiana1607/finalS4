<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\Operateur\TrancheModel;
use App\Models\Operateur\TypeOperationModel;

class FraisController extends BaseController
{
    protected TrancheModel $trancheModel;
    protected TypeOperationModel $typeOperationModel;

    public function __construct()
    {
        $this->trancheModel       = new TrancheModel();
        $this->typeOperationModel = new TypeOperationModel();
    }

    public function index()
    {
        $types       = $this->typeOperationModel->findAll();
        $typeId      = (int) ($this->request->getGet('type') ?? ($types[0]['id'] ?? 1));
        $tranches    = $this->trancheModel->getByTypeOperation($typeId);

        $data = [
            'types'     => $types,
            'typeId'    => $typeId,
            'tranches'  => $tranches,
        ];

        return view('admin/frais', $data);
    }

    public function store()
    {
        $rules = [
            'type_operation_id' => 'required|integer',
            'montant_min'       => 'required|numeric',
            'montant_max'       => 'required|numeric',
            'frais'             => 'required|numeric',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $montantMin = (float) $this->request->getPost('montant_min');
        $montantMax = (float) $this->request->getPost('montant_max');
        $typeId     = (int) $this->request->getPost('type_operation_id');

        if ($montantMin >= $montantMax) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Le montant minimum doit être strictement inférieur au montant maximum.');
        }

        $saved = $this->trancheModel->insert([
            'type_operation_id' => $typeId,
            'montant_min'       => $montantMin,
            'montant_max'       => $montantMax,
            'frais'             => (float) $this->request->getPost('frais'),
        ]);

        if (! $saved) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'ajout de la tranche.');
        }

        return redirect()->to('/admin/frais?type=' . $typeId)
                         ->with('success', 'Tranche ajoutée avec succès.');
    }

    public function edit(int $id)
    {
        $tranche = $this->trancheModel->find($id);

        if (! $tranche) {
            return redirect()->to('/admin/frais')->with('error', 'Tranche introuvable.');
        }

        $types = $this->typeOperationModel->findAll();

        $data = [
            'tranche' => $tranche,
            'types'   => $types,
        ];

        return view('admin/popup/frais_edit_popup', $data);
    }

    public function update(int $id)
    {
        $rules = [
            'type_operation_id' => 'required|integer',
            'montant_min'       => 'required|numeric',
            'montant_max'       => 'required|numeric',
            'frais'             => 'required|numeric',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $montantMin = (float) $this->request->getPost('montant_min');
        $montantMax = (float) $this->request->getPost('montant_max');
        $typeId     = (int) $this->request->getPost('type_operation_id');

        if ($montantMin >= $montantMax) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Le montant minimum doit être strictement inférieur au montant maximum.');
        }

        $updated = $this->trancheModel->update($id, [
            'type_operation_id' => $typeId,
            'montant_min'       => $montantMin,
            'montant_max'       => $montantMax,
            'frais'             => (float) $this->request->getPost('frais'),
        ]);

        if (! $updated) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la modification de la tranche.');
        }

        return redirect()->to('/admin/frais?type=' . $typeId)
                         ->with('success', 'Tranche modifiée avec succès.');
    }

    public function delete(int $id)
    {
        $tranche = $this->trancheModel->find($id);

        if (! $tranche) {
            return redirect()->to('/admin/frais')->with('error', 'Tranche introuvable.');
        }

        $typeId = $tranche['type_operation_id'];

        if (! $this->trancheModel->delete($id)) {
            return redirect()->to('/admin/frais?type=' . $typeId)
                             ->with('error', 'Impossible de supprimer cette tranche.');
        }

        return redirect()->to('/admin/frais?type=' . $typeId)
                         ->with('success', 'Tranche supprimée avec succès.');
    }
}
