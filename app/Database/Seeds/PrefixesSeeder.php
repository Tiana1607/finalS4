<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PrefixesSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('prefixes')->truncate();

        // operateur_id: 1 = MoovMoney (nous), 2 = Airtel Money, 3 = Telma
        $data = [
            ['prefixe' => '033', 'operateur_id' => 1],
            ['prefixe' => '037', 'operateur_id' => 1],
            ['prefixe' => '032', 'operateur_id' => 2],
            ['prefixe' => '031', 'operateur_id' => 3],
        ];

        $this->db->table('prefixes')->insertBatch($data);
    }
}
