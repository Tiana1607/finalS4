<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TypesOperationSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('types_operation')->truncate();

        $data = [
            ['libelle' => 'depot'],
            ['libelle' => 'retrait'],
            ['libelle' => 'transfert'],
        ];

        $this->db->table('types_operation')->insertBatch($data);
    }
}