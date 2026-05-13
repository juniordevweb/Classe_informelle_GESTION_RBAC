<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStructuresTable extends Migration
{
    public function up()
    {
        $this->forge->dropTable('structures', true);
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'code_structure' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'nom_structure' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'region' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'departement' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'commune' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'quartier' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'ia' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'ief' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'latitude' => [
                'type' => 'DECIMAL',
                'constraint' => '10,8',
                'null' => true,
            ],
            'longitude' => [
                'type' => 'DECIMAL',
                'constraint' => '11,8',
                'null' => true,
            ],
            'langue_nationale' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'operateur_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'etat' => [
                'type' => 'ENUM',
                'constraint' => ['EN_ATTENTE', 'VALIDE', 'OUVERT', 'FERME', 'GELE'],
                'default' => 'EN_ATTENTE',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code_structure');
        // Foreign key vers operateur (singulier, pas operateurs)
        // $this->forge->addForeignKey('operateur_id', 'operateur', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('structures');
    }

    public function down()
    {
        $this->forge->dropTable('structures');
    }
}