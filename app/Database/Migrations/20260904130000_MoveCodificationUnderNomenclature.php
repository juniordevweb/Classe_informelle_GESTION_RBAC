<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * La codification sera gérée depuis la section Nomenclature.
 * L'ancien sous-menu autonome est conservé en base mais masqué pour ne pas
 * supprimer les données ni empêcher sa réactivation lors d'une évolution.
 */
class MoveCodificationUnderNomenclature extends Migration
{
    public function up()
    {
        $subMenu = $this->db->table('sous_menus')
            ->select('id')
            ->groupStart()
            ->where('url', '/configuration/codification')
            ->orWhere('url', 'configuration/codification')
            ->groupEnd()
            ->get()
            ->getRowArray();

        if (! $subMenu) {
            return;
        }

        $subMenuId = (int) $subMenu['id'];
        $this->db->table('sous_menus')->where('id', $subMenuId)->update(['statut' => 0]);
        $this->db->table('role_permissions')->where('sous_menu_id', $subMenuId)->delete();
    }

    public function down()
    {
        $subMenu = $this->db->table('sous_menus')
            ->select('id, menu_id')
            ->groupStart()
            ->where('url', '/configuration/codification')
            ->orWhere('url', 'configuration/codification')
            ->groupEnd()
            ->get()
            ->getRowArray();

        if (! $subMenu) {
            return;
        }

        $subMenuId = (int) $subMenu['id'];
        $menuId = (int) $subMenu['menu_id'];
        $this->db->table('sous_menus')->where('id', $subMenuId)->update(['statut' => 1]);

        for ($permissionId = 1; $permissionId <= 4; $permissionId++) {
            $this->db->table('role_permissions')->insert([
                'role_id' => 1,
                'menu_id' => $menuId,
                'sous_menu_id' => $subMenuId,
                'permission_id' => $permissionId,
            ]);
        }
    }
}
