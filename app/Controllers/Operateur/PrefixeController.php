<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\Operateur\PrefixeModel;
use App\Models\OperateurModel;

class PrefixeController extends BaseController
{
    protected PrefixeModel $prefixeModel;
    protected OperateurModel $operateurModel;

    public function __construct()
    {
        $this->prefixeModel = new PrefixeModel();
        $this->operateurModel = new OperateurModel();
    }   

    public function index()
    {
        $data = [
            'nosPrefixes'    => $this->prefixeModel->getNosPrefixes(),
            'autresPrefixes' => $this->prefixeModel->getAutresPrefixes(),
            'operateurs'     => $this->operateurModel->getOperateursExterne(),
            'operateurNous'  => $this->operateurModel->getNotreOperateur(),
        ];

        return view('admin/prefixes', $data);
    }

    public function store()
    {
        $rules = [
            'prefixe'      => 'required|min_length[2]|max_length[5]|is_unique[prefixes.prefixe]',
            'appartenance' => 'required|in_list[nous,autre]',
        ];

        // operateur_id requis seulement si "autre" est choisi
        if ($this->request->getPost('appartenance') === 'autre') {
            $rules['operateur_id'] = 'required|is_natural_no_zero';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        if ($this->request->getPost('appartenance') === 'nous') {
            $operateurNous = $this->operateurModel->getNous();
            $operateurId   = $operateurNous['id'];
        } else {
            $operateurId = $this->request->getPost('operateur_id');
        }

        $this->prefixeModel->insert([
            'prefixe'      => $this->request->getPost('prefixe'),
            'operateur_id' => $operateurId,
        ]);

        return redirect()->to('/admin/prefixes')->with('success', 'Préfixe ajouté avec succès.');
    }

    public function delete(int $id)
    {
        $this->prefixeModel->delete($id);
        return redirect()->to('/admin/prefixes')->with('success', 'Préfixe supprimé.');
    }
}
