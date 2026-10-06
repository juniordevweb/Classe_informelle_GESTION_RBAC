<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ChangeConfigurationMenuIcon extends Migration
{
    public function up()
    {
        $this->db->table('menus')
            ->where('nom_menu', 'Configuration')
            ->update(['icone' => 'fa fa-sliders-h']);
    }

    public function down()
    {
        $this->db->table('menus')
            ->where('nom_menu', 'Configuration')
            ->update(['icone' => 'fa fa-cogs']);
    }
}
