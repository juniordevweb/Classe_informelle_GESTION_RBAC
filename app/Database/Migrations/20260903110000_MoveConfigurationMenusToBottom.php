<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MoveConfigurationMenusToBottom extends Migration
{
    public function up()
    {
        $menus = $this->db->table('menus');
        $last = $menus->selectMax('ordre', 'last_order')->get()->getRowArray();
        $lastOrder = (int) ($last['last_order'] ?? 0);

        // Configuration juste avant Paramètres, tous deux après les autres menus.
        $menus->where('nom_menu', 'Configuration')->update(['ordre' => $lastOrder + 1]);
        $menus->whereIn('id', [6])->orWhere('nom_menu', 'Paramètres')->orWhere('nom_menu', 'Parametres')
            ->update(['ordre' => $lastOrder + 2]);
    }

    public function down()
    {
        // L'ordre précédent dépend des données présentes avant cette migration.
    }
}
