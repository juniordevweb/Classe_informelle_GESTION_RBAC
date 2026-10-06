<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNomenclatureOfferFields extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('nomenclatures')) {
            return;
        }

        $columns = [];
        if (! $this->db->fieldExists('type_offre_id', 'nomenclatures')) {
            $columns['type_offre_id'] = ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true, 'after' => 'id'];
        }
        if (! $this->db->fieldExists('nom_site', 'nomenclatures')) {
            $columns['nom_site'] = ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'libelle'];
        }
        if ($columns !== []) {
            $this->forge->addColumn('nomenclatures', $columns);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('nomenclatures')) {
            $columns = [];
            if ($this->db->fieldExists('type_offre_id', 'nomenclatures')) $columns[] = 'type_offre_id';
            if ($this->db->fieldExists('nom_site', 'nomenclatures')) $columns[] = 'nom_site';
            if ($columns !== []) $this->forge->dropColumn('nomenclatures', $columns);
        }
    }
}
