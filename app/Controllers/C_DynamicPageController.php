<?php

namespace App\Controllers;

use App\Models\M_MenuModel;
use App\Models\M_SousMenuModel;

class C_DynamicPageController extends BaseController
{
    protected M_MenuModel $menuModel;
    protected M_SousMenuModel $sousMenuModel;

    public function __construct()
    {
        $this->menuModel = new M_MenuModel();
        $this->sousMenuModel = new M_SousMenuModel();
    }

    public function show(string $type = 'menu', int $id = 0)
    {
        $data['user_permissions'] = $this->getUserPermissions();

        if ($type === 'submenu') {
            $record = $this->sousMenuModel
                ->select('sous_menus.*, menus.nom_menu AS parent_menu')
                ->join('menus', 'menus.id = sous_menus.menu_id', 'left')
                ->where('sous_menus.id', $id)
                ->first();

            if (! $record) {
                return redirect()->to('/dashboard')->with('error', 'Page introuvable.');
            }

            $data['pageTitle'] = $record['nom_sous_menu'] ?? 'Sous-menu';
            $data['pageSubtitle'] = $record['parent_menu'] ?? '';
            $data['pageUrl'] = trim((string) ($record['url'] ?? ''), '/');
            $data['pageType'] = 'Sous-menu';
            $data['record'] = $record;

            return view('V_DynamicPage', $data);
        }

        $record = $this->menuModel->find($id);

        if (! $record) {
            return redirect()->to('/dashboard')->with('error', 'Page introuvable.');
        }

        $data['pageTitle'] = $record['nom_menu'] ?? 'Menu';
        $data['pageSubtitle'] = '';
        $data['pageUrl'] = trim((string) ($record['url'] ?? ''), '/');
        $data['pageType'] = 'Menu';
        $data['record'] = $record;

        return view('V_DynamicPage', $data);
    }
}
