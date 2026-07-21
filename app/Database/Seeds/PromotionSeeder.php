<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CommissionsSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('Promotion')->truncate();

        $data = [
            ['operateur_id' => 1, 'pourcentage' => 10.0],
        ];

        $this->db->table('Promotion')->insertBatch($data);
    }

    
}
