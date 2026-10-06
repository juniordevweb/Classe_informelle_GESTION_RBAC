<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UseStringOfferCodes extends Migration
{
    public function up()
    {
        foreach (['types_offre', 'nomenclatures', 'regles_codification'] as $table) {
            if ($this->db->tableExists($table) && $this->db->fieldExists('code', $table)) {
                $this->db->query("ALTER TABLE {$table} MODIFY code VARCHAR(50) NOT NULL");
            }
        }
    }

    public function down()
    {
        // Les codes de 10 chiffres doivent rester stockés en texte.
    }
}
