<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use \CodeIgniter\Database\RawSql;

class CreateClientsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'telephone' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'solde' => [
                'type' => 'REAL',
                'null' => false,
                'default' => 0,
            ],
            'date_creation' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('telephone');

        $this->forge->createTable('clients');
    }

    public function down()
    {
        $this->forge->dropTable('clients');
    }
}
