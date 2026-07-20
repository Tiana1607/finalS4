<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TypeOperationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['libelle' => 'depot'],
            ['libelle' => 'retrait'],
            ['libelle' => 'transfert'],
        ];

        $this->db->table('types_operation')->insertBatch($data);
    }
}
