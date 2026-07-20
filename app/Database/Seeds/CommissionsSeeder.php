<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CommissionsSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('commissions_externes')->truncate();

        // operateur_id: 2 = Airtel Money, 3 = Telma
        $data = [
            ['operateur_id' => 2, 'pourcentage' => 2.0],
            ['operateur_id' => 3, 'pourcentage' => 1.5],
        ];

        $this->db->table('commissions_externes')->insertBatch($data);
    }
}
