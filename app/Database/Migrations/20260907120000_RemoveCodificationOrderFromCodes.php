<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveCodificationOrderFromCodes extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('regles_codification')) {
            return;
        }

        $index = $this->db->query("SHOW INDEX FROM regles_codification WHERE Key_name = 'uq_regles_codification_code'")->getRowArray();
        if ($index !== null) {
            $this->db->query('ALTER TABLE regles_codification DROP INDEX uq_regles_codification_code');
        }

        if ($this->db->fieldExists('type_offre_id', 'regles_codification') && $this->db->tableExists('types_offre')) {
            $this->db->query(
                'UPDATE regles_codification rc
                 INNER JOIN types_offre toffre ON toffre.id = rc.type_offre_id
                 SET rc.code = toffre.code'
            );
        }
    }

    public function down()
    {
        // Les codes de codification doivent rester égaux au code du type d’offre.
    }
}
