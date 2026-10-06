<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePermissionsTable extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('permissions')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'nom_permission' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => false,
                ],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable('permissions', true, [
                'ENGINE'          => 'InnoDB',
                'DEFAULT CHARSET' => 'utf8',
                'COLLATE'         => 'utf8_general_ci',
            ]);
        }

        $permissions = [
            1 => 'Consulter',
            2 => 'Ajouter',
            3 => 'Modifier',
            4 => 'Supprimer',
        ];

        foreach ($permissions as $id => $name) {
            $exists = $this->db->table('permissions')->where('id', $id)->countAllResults();

            if ($exists === 0) {
                $this->db->table('permissions')->insert([
                    'id'             => $id,
                    'nom_permission' => $name,
                ]);
            }
        }
    }

    public function down()
    {
        // Keep the permission catalog: role_permissions may still reference its IDs.
    }
}
