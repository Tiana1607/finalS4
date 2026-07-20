<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddV2Columns extends Migration
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
        $this->forge->addForeignKey('operateur_id', 'operateurs', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('commissions_externes');

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
            'montant_total' => [
                'type' => 'REAL',
                'null' => false,
            ],
            'nb_destinataires' => [
                'type' => 'INTEGER',
                'unsigned' => true,
                'null' => false,
            ],
            'date_operation' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('client_id', 'clients', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('transferts_groupes');

        $this->db->query('ALTER TABLE prefixes ADD COLUMN operateur_id INTEGER REFERENCES operateurs(id)');

        $this->db->query('ALTER TABLE transactions ADD COLUMN operateur_destinataire_id INTEGER REFERENCES operateurs(id)');
        $this->db->query('ALTER TABLE transactions ADD COLUMN commission_externe REAL DEFAULT 0');
        $this->db->query('ALTER TABLE transactions ADD COLUMN groupe_id INTEGER REFERENCES transferts_groupes(id)');
    }

    public function down()
    {
        $cols = [
            'groupe_id',
            'commission_externe',
            'operateur_destinataire_id',
        ];
        foreach ($cols as $col) {
            $this->db->query("ALTER TABLE transactions DROP COLUMN {$col}");
        }

        $this->db->query('ALTER TABLE prefixes DROP COLUMN operateur_id');

        $this->forge->dropTable('transferts_groupes');
        $this->forge->dropTable('commissions_externes');
    }
}
