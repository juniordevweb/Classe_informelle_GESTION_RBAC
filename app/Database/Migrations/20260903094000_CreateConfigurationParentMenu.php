<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConfigurationParentMenu extends Migration
{
    private array $slugs = [
        'types-offre', 'langues', 'sites', 'statuts', 'financements',
        'programmes', 'etats', 'nomenclature', 'codification',
    ];

    public function up()
    {
        $parametres = $this->db->table('menus')->where('id', 6)->get()->getRowArray();
        if (! $parametres) {
            return;
        }

        $menus = $this->db->table('menus');
        $configuration = $menus->where('nom_menu', 'Configuration')->get()->getRowArray();

        if ($configuration) {
            $configurationId = (int) $configuration['id'];
        } else {
            $parametresOrder = (int) ($parametres['ordre'] ?? 0);
            $menus->where('id', (int) $parametres['id'])->set('ordre', $parametresOrder + 1)->update();
            $menus->insert([
                'nom_menu' => 'Configuration',
                'url' => null,
                'icone' => 'fa fa-cogs',
                'ordre' => $parametresOrder,
                'permission_id' => 1,
                'statut' => 1,
            ]);
            $configurationId = (int) $this->db->insertID();
        }

        // L'ancien lien Configuration des Structures est remplacé par le menu parent.
        $this->db->table('sous_menus')
            ->groupStart()
            ->where('url', '/parametres/configuration-structures')
            ->orWhere('url', 'parametres/configuration-structures')
            ->groupEnd()->update(['statut' => 0]);

        $labels = [
            'types-offre' => ["Types d’offre", 'fa fa-school', 1],
            'langues' => ['Langues nationales', 'fa fa-language', 2],
            'sites' => ['Sites abritants', 'fa fa-building', 3],
            'statuts' => ["Statuts d’occupation", 'fa fa-home', 4],
            'financements' => ['Sources de financement', 'fa fa-money', 5],
            'programmes' => ['Programmes', 'fa fa-sitemap', 6],
            'etats' => ['États des structures', 'fa fa-check-circle', 7],
            'nomenclature' => ['Nomenclature', 'fa fa-list', 8],
            'codification' => ['Codification', 'fa fa-barcode', 9],
        ];

        foreach ($this->slugs as $slug) {
            $url = '/configuration/' . $slug;
            $subMenu = $this->db->table('sous_menus')->where('url', $url)->get()->getRowArray();

            if ($subMenu) {
                $subMenuId = (int) $subMenu['id'];
                $this->db->table('sous_menus')->where('id', $subMenuId)->update(['menu_id' => $configurationId, 'statut' => 1]);
            } else {
                [$label, $icon, $order] = $labels[$slug];
                $this->db->table('sous_menus')->insert([
                    'menu_id' => $configurationId,
                    'nom_sous_menu' => $label,
                    'url' => $url,
                    'icon' => $icon,
                    'ordre' => $order,
                    'permission_id' => 1,
                    'statut' => 1,
                ]);
                $subMenuId = (int) $this->db->insertID();
            }

            for ($permissionId = 1; $permissionId <= 4; $permissionId++) {
                $exists = $this->db->table('role_permissions')->where([
                    'role_id' => 1,
                    'menu_id' => $configurationId,
                    'sous_menu_id' => $subMenuId,
                    'permission_id' => $permissionId,
                ])->countAllResults();
                if ($exists === 0) {
                    $this->db->table('role_permissions')->insert([
                        'role_id' => 1,
                        'menu_id' => $configurationId,
                        'sous_menu_id' => $subMenuId,
                        'permission_id' => $permissionId,
                    ]);
                }
            }
        }
    }

    public function down()
    {
        $configuration = $this->db->table('menus')->where('nom_menu', 'Configuration')->get()->getRowArray();
        if (! $configuration) {
            return;
        }

        $configurationId = (int) $configuration['id'];
        $subMenus = $this->db->table('sous_menus');
        $children = $subMenus->select('id')->where('menu_id', $configurationId)->get()->getResultArray();
        foreach ($children as $child) {
            $this->db->table('role_permissions')->where(['menu_id' => $configurationId, 'sous_menu_id' => (int) $child['id']])->delete();
        }
        $subMenus->where('menu_id', $configurationId)->delete();
        $this->db->table('menus')->where('id', $configurationId)->delete();
        $this->db->table('sous_menus')->groupStart()->where('url', '/parametres/configuration-structures')->orWhere('url', 'parametres/configuration-structures')->groupEnd()->update(['statut' => 1]);
    }
}
