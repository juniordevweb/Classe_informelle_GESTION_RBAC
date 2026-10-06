<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UseAvailableConfigurationIcon extends Migration
{
    public function up()
    {
        $this->db->table('menus')
            ->where('nom_menu', 'Configuration')
            ->update(['icone' => 'md md-tune']);
    }

    public function down()
    {
        $this->db->table('menus')
            ->where('nom_menu', 'Configuration')
            ->update(['icone' => 'fa fa-sliders-h']);
    }
}
