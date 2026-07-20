<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OperateursSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('operateurs')->truncate();

        $data = [
            ['nom' => 'MoovMoney',   'est_nous' => 1],
            ['nom' => 'Airtel Money', 'est_nous' => 0],
            ['nom' => 'Telma',        'est_nous' => 0],
        ];

        $this->db->table('operateurs')->insertBatch($data);
    }
}
