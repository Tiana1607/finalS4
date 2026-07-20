<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTrancheMontantTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'type_operation_id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
                'null' => false,
            ],
            'montant_min' => [
                'type' => 'REAL',
                'null' => false,
            ],
            'montant_max' => [
                'type' => 'REAL',
                'null' => false,
            ],
            'frais' => [
                'type' => 'REAL',
                'null' => false,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('type_operation_id', 'types_operation', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('tranche_montant');
    }

    public function down()
    {
        $this->forge->dropTable('tranche_montant');
    }
}
