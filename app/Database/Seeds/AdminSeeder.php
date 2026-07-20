<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('admins')->truncate();
        $data = [
            'nom'          => 'Admin',
            'email'        => 'admin@example.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT), // hashé au moment de l'exécution
        ];

        $this->db->table('admins')->insert($data);
    }
}
