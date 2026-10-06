<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UniqueOfferTypeLabels extends Migration
{
    public function up()
    {
        // Désactivé : l'ALTER TABLE fait tomber MariaDB sur la table
        // types_offre existante. Cette contrainte n'est pas nécessaire au
        // fonctionnement de la codification.
        return;
    }

    public function down()
    {
        if ($this->db->tableExists('types_offre')) {
            $this->db->query('ALTER TABLE types_offre DROP INDEX uq_types_offre_libelle');
        }
    }
}
