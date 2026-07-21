<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EpargneTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'client_id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
                'null' => false,
            ],
            'pourcentage' => [
                'type' => 'REAL',
                'null' => false,
                'default' => 0,
            ],
            'date_creation' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('client_id', 'clients', 'id', 'SET NULL', 'CASCADE');

        $this->forge->createTable('epargne');
    }

    public function down()
    {
        $this->forge->dropTable('epargne');
    }
}
