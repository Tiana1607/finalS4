<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOperateursTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nom' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'est_nous' => [
                'type' => 'INTEGER',
                'null' => false,
                'default' => 0,
            ],
        ]);
        $this->forge->addKey('id', true);

        $this->forge->createTable('operateurs');
    }

    public function down()
    {
        $this->forge->dropTable('operateurs');
    }
}
