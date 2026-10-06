<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCodificationStructureFields extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('type_offre_id', 'regles_codification')) {
            $this->forge->addColumn('regles_codification', [
                'type_offre_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'after' => 'id'],
                'nom_site' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'libelle'],
            ]);
        }

        // Le code de structure est la clé métier : il ne peut être attribué qu'une fois.
        $this->db->query('ALTER TABLE regles_codification ADD UNIQUE KEY uq_regles_codification_code (code)');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE regles_codification DROP INDEX uq_regles_codification_code');
        if ($this->db->fieldExists('type_offre_id', 'regles_codification')) {
            $this->forge->dropColumn('regles_codification', ['type_offre_id', 'nom_site']);
        }
    }
}
