<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('TypesOperationSeeder');
        $this->call('OperateursSeeder');
        $this->call('PrefixesSeeder');
        $this->call('CommissionsSeeder');
        $this->call('TrancheMontantSeeder');
        $this->call('AdminSeeder');
        $this
    }
}
