<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use App\Models\Operateur\PrefixeModel;

class PrefixeController extends BaseController
{
    protected PrefixeModel $prefixeModel;

    public function __construct()
    {
        $this->prefixeModel = new PrefixeModel();
    }

    public function index()
    {
        $data = [
            'prefixes' => $this->prefixeModel->findAll(),
        ];

        return view('admin/prefixes', $data);
    }

    public function store()
    {
        $rules = [
            'prefixe' => 'required|min_length[3]|max_length[10]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $prefixe = $this->request->getPost('prefixe');

        if (! $this->prefixeModel->insert(['prefixe' => $prefixe])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ce préfixe existe déjà.');
        }

        return redirect()->to('/admin/prefixes')->with('success', 'Préfixe ajouté avec succès.');
    }

    public function delete(int $id)
    {
        if (! $this->prefixeModel->delete($id)) {
            return redirect()->back()->with('error', 'Impossible de supprimer ce préfixe.');
        }

        return redirect()->to('/admin/prefixes')->with('success', 'Préfixe supprimé avec succès.');
    }
}
