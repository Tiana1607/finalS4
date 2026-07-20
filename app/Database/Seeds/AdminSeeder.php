<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'nom'          => 'Admin',
            'email'        => 'admin@example.com',
            'mot_de_passe' => password_hash('admin123', PASSWORD_DEFAULT), // hashé au moment de l'exécution
        ];

        $this->db->table('admins')->insert($data);
    }
}
