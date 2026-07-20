<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PrefixesSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('prefixes')->truncate();
        
        $data = [
            ['prefixe' => '033'],
            ['prefixe' => '037'],
        ];

        $this->db->table('prefixes')->insertBatch($data);
    }
}
