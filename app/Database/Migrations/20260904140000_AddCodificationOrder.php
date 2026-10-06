<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCodificationOrder extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('regles_codification') && ! $this->db->fieldExists('numero_ordre', 'regles_codification')) {
            $this->forge->addColumn('regles_codification', [
                'numero_ordre' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'after' => 'type_offre_id'],
            ]);

        }
    }

    public function down()
    {
        if ($this->db->tableExists('regles_codification') && $this->db->fieldExists('numero_ordre', 'regles_codification')) {
            $this->forge->dropColumn('regles_codification', 'numero_ordre');
        }
    }
}
