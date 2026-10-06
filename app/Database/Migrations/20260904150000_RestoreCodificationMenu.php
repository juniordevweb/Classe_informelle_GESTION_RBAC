<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RestoreCodificationMenu extends Migration
{
    public function up()
    {
        $menu = $this->db->table('menus')->where('nom_menu', 'Configuration')->get()->getRowArray();
        if (! $menu) {
            return;
        }

        $subMenu = $this->db->table('sous_menus')
            ->where('menu_id', (int) $menu['id'])
            ->groupStart()->where('url', '/configuration/codification')->orWhere('url', 'configuration/codification')->groupEnd()
            ->get()->getRowArray();
        if (! $subMenu) {
            return;
        }

        $this->db->table('sous_menus')->where('id', (int) $subMenu['id'])->update(['statut' => 1]);
        for ($permissionId = 1; $permissionId <= 4; $permissionId++) {
            $exists = $this->db->table('role_permissions')->where([
                'role_id' => 1, 'menu_id' => (int) $menu['id'], 'sous_menu_id' => (int) $subMenu['id'], 'permission_id' => $permissionId,
            ])->countAllResults();
            if ($exists === 0) {
                $this->db->table('role_permissions')->insert([
                    'role_id' => 1, 'menu_id' => (int) $menu['id'], 'sous_menu_id' => (int) $subMenu['id'], 'permission_id' => $permissionId,
                ]);
            }
        }
    }

    public function down()
    {
        $subMenu = $this->db->table('sous_menus')->where('url', '/configuration/codification')->get()->getRowArray();
        if ($subMenu) {
            $this->db->table('sous_menus')->where('id', (int) $subMenu['id'])->update(['statut' => 0]);
        }
    }
}
