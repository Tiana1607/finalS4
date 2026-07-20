<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Client\ClientsModel;
use App\Models\PrefixesModel;

class AuthController extends BaseController
{
    protected ClientsModel $clientsModel;
    protected PrefixesModel $prefixesModel;

    public function __construct()
    {
        $this->clientsModel  = new ClientsModel();
        $this->prefixesModel = new PrefixesModel();
    }

    public function showLoginForm()
    {
        return view('client/login');
    }

    public function login()
    {
        $telephone = $this->request->getPost('tel');

        if (empty(trim($telephone))) {
            return redirect()->back()->withInput()->with('error', 'Veuillez saisir votre numéro de téléphone.');
        }

        $telephone = preg_replace('/\s+/', '', trim($telephone));
        if (!preg_match('/^\d{10}$/', $telephone)) {
            return redirect()->back()->withInput()->with('error', 'Le numéro doit contenir exactement 10 chiffres (ex : 032 12 123 12).');
        }

        if (!$this->prefixesModel->prefixeExiste($telephone)) {
            return redirect()->back()->withInput()->with('error', 'Préfixe inconnu. Veuillez vérifier votre numéro.');
        }

        if (!$this->prefixesModel->prefixeEstANous($telephone)) {
            $this->clientsModel->findOrCreateByTelephone($telephone);
            return redirect()->back()->withInput()->with('error', 'Préfixe invalide. Vous ne pouvez vous connecter qu\'avec un numéro de notre opérateur. Préfixes valides : 033, 037.');
        }

        $client = $this->clientsModel->findOrCreateByTelephone($telephone);

        session()->set([
            'client_id' => $client['id'],
            'telephone' => $client['telephone'],
            'logged_in' => true,
        ]);

        return redirect()->to('/client/dashboard');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/');
    }

    public function dashboard()
    {
        $client = $this->clientsModel->find(session()->get('client_id'));

        return view('client/dashboard', ['client' => $client]);
    }
}
