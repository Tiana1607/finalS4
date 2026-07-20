<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeeder extends Seeder
{
    public function run()
    {
        $this->call('TypesOperationSeeder');
        $this->call('PrefixesSeeder');
        $this->call('TrancheMontantSeeder');
        $this->call('AdminSeeder');
    }
}
