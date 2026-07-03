<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ExpandMenusForDynamicSidebar extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('permission_id', 'menus')) {
            $this->forge->addColumn('menus', [
                'permission_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => false,
                    'default' => 1,
                    'after' => 'ordre',
                ],
            ]);
        }

        if (! $this->db->fieldExists('statut', 'menus')) {
            $this->forge->addColumn('menus', [
                'statut' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'null' => false,
                    'default' => 1,
                    'after' => 'permission_id',
                ],
            ]);
        }

        if (! $this->db->fieldExists('permission_id', 'sous_menus')) {
            $this->forge->addColumn('sous_menus', [
                'permission_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => false,
                    'default' => 1,
                    'after' => 'ordre',
                ],
            ]);
        }

        if (! $this->db->fieldExists('statut', 'sous_menus')) {
            $this->forge->addColumn('sous_menus', [
                'statut' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'null' => false,
                    'default' => 1,
                    'after' => 'permission_id',
                ],
            ]);
        }

        $this->db->table('menus')->set([
            'permission_id' => 1,
            'statut' => 1,
        ])->update();

        $this->db->table('sous_menus')->set([
            'permission_id' => 1,
            'statut' => 1,
        ])->update();

        $this->db->table('sous_menus')->where('id', 11)->update([
            'nom_sous_menu' => 'Gestion users',
            'url' => '/users',
            'icon' => 'fa fa-users',
            'ordre' => 1,
        ]);

        $this->db->table('sous_menus')->where('id', 12)->update([
            'nom_sous_menu' => 'Gestion profil',
            'url' => '/profils',
            'icon' => 'fa fa-user-shield',
            'ordre' => 2,
        ]);

        $this->db->table('sous_menus')->where('id', 13)->update([
            'nom_sous_menu' => 'Apprenant',
            'url' => '/apprenant',
            'icon' => 'fa fa-user-graduate',
            'ordre' => 1,
        ]);

        $this->db->table('sous_menus')->where('id', 14)->update([
            'nom_sous_menu' => 'Classes',
            'url' => '/classes',
            'icon' => 'fa fa-chalkboard',
            'ordre' => 1,
        ]);

        $this->db->table('sous_menus')->where('id', 15)->update([
            'nom_sous_menu' => 'Liste des structures',
            'url' => '/structures',
            'icon' => 'fa fa-building',
            'ordre' => 1,
        ]);

        if ($this->db->table('sous_menus')->where('id', 17)->countAllResults() === 0) {
            $this->db->table('sous_menus')->insert([
                'id' => 17,
                'menu_id' => 6,
                'nom_sous_menu' => 'Gestion menus',
                'url' => '/menus',
                'icon' => 'fa fa-sitemap',
                'ordre' => 3,
                'permission_id' => 1,
                'statut' => 1,
            ]);
        }

        $rolePermissions = $this->db->table('role_permissions');
        $existing = $rolePermissions->where([
            'role_id' => 1,
            'menu_id' => 6,
            'sous_menu_id' => 17,
        ])->countAllResults();

        if ($existing === 0) {
            $rolePermissions->insertBatch([
                ['role_id' => 1, 'menu_id' => 6, 'sous_menu_id' => 17, 'permission_id' => 1],
                ['role_id' => 1, 'menu_id' => 6, 'sous_menu_id' => 17, 'permission_id' => 2],
                ['role_id' => 1, 'menu_id' => 6, 'sous_menu_id' => 17, 'permission_id' => 3],
                ['role_id' => 1, 'menu_id' => 6, 'sous_menu_id' => 17, 'permission_id' => 4],
            ]);
        }
    }

    public function down()
    {
        $this->db->table('role_permissions')
            ->where('menu_id', 6)
            ->where('sous_menu_id', 17)
            ->delete();

        $this->db->table('sous_menus')->where('id', 17)->delete();

        if ($this->db->fieldExists('statut', 'sous_menus')) {
            $this->forge->dropColumn('sous_menus', 'statut');
        }

        if ($this->db->fieldExists('permission_id', 'sous_menus')) {
            $this->forge->dropColumn('sous_menus', 'permission_id');
        }

        if ($this->db->fieldExists('statut', 'menus')) {
            $this->forge->dropColumn('menus', 'statut');
        }

        if ($this->db->fieldExists('permission_id', 'menus')) {
            $this->forge->dropColumn('menus', 'permission_id');
        }
    }
}
