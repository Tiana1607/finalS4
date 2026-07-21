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
            'operateur_id' => [
                'type' => 'INtEger',
                'null' => false,
            ],
            'pourcentage' => [
                'type' => 'INTEGER',
                'null' => false,
                'default' => 0,
            ],
        ]);
        $this->forge->addKey('id', true);

        $this->forge->createTable('Promotion');
    }

    public function down()
    {
        $this->forge->dropTable('Promotion');
    }
}
