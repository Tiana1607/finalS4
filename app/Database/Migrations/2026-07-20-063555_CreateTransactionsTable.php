<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use \CodeIgniter\Database\RawSql;

class CreateTransactionsTable extends Migration
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
            'destinataire_id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
                'null' => true,
            ],
            'type_operation_id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
                'null' => false,
            ],
            'montant' => [
                'type' => 'REAL',
                'null' => false,
            ],
            'frais' => [
                'type' => 'REAL',
                'null' => false,
            ],
            'date_operation' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('client_id', 'clients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('type_operation_id', 'types_operation', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('destinataire_id', 'clients', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropTable('transactions');
    }
}
