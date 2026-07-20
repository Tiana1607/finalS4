<?php

namespace App\Controllers\Operateur;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\AdminModel;

class AuthController extends BaseController
{
    protected AdminModel $adminModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    public function login()
    {
        return view('admin/login');
    }

    public function attemptLogin()
    {
        $rules = [
            'email'        => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $email       = $this->request->getPost('email');
        $motDePasse  = $this->request->getPost('password');

        $admin = $this->adminModel->findByEmail($email);

        if (! $admin || ! password_verify($motDePasse, $admin['password'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email ou mot de passe incorrect.');
        }

        session()->set([
            'admin_id'        => $admin['id'],
            'admin_nom'       => $admin['nom'],
            'isAdminLoggedIn' => true,
        ]);

        return redirect()->to('/admin/prefixes');
    }

    public function logout()
    {
        session()->remove(['admin_id', 'admin_nom', 'isAdminLoggedIn']);
        return redirect()->to('/admin/login')->with('success', 'Déconnexion réussie.');
    }
}
