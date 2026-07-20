<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PrefixSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['prefixe' => '033'],
            ['prefixe' => '037'],
        ];

        $this->db->table('prefixes')->insertBatch($data);
    }
}
