<?php

namespace App\Controllers;

use App\Models\M_MenuModel;
use App\Models\M_PermissionModel;
use App\Models\M_SousMenuModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Config\Database;

class C_MenuController extends BaseController
{
    protected M_MenuModel $menuModel;
    protected M_SousMenuModel $sousMenuModel;
    protected M_PermissionModel $permissionModel;
    protected $db;

    public function __construct()
    {
        $this->menuModel = new M_MenuModel();
        $this->sousMenuModel = new M_SousMenuModel();
        $this->permissionModel = new M_PermissionModel();
        $this->db = Database::connect();
    }

    public function index()
    {
        $data['user_permissions'] = $this->getUserPermissions();
        $data['menus'] = $this->menuModel->orderBy('ordre', 'ASC')->orderBy('id', 'ASC')->findAll();
        $data['sous_menus'] = $this->sousMenuModel
            ->select('sous_menus.*, menus.nom_menu AS parent_menu')
            ->join('menus', 'menus.id = sous_menus.menu_id', 'left')
            ->orderBy('sous_menus.menu_id', 'ASC')
            ->orderBy('sous_menus.ordre', 'ASC')
            ->orderBy('sous_menus.id', 'ASC')
            ->findAll();
        $data['permissions'] = $this->permissionModel->orderBy('id', 'ASC')->findAll();

        return view('V_GestionMenus', $data);
    }

    public function saveMenu()
    {
        if (! $this->validate($this->menuRules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->menuModel->insert($this->menuPayload());
        } catch (DatabaseException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Impossible de créer le menu.');
        }

        return redirect()->to('/menus')
            ->with('success', 'Menu créé avec succès.');
    }

    public function updateMenu()
    {
        $id = (int) $this->request->getPost('id');

        if ($id <= 0 || ! $this->menuModel->find($id)) {
            return redirect()->to('/menus')->with('error', 'Menu introuvable.');
        }

        if (! $this->validate($this->menuRules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->menuModel->update($id, $this->menuPayload());
        } catch (DatabaseException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Impossible de modifier le menu.');
        }

        return redirect()->to('/menus')
            ->with('success', 'Menu modifié avec succès.');
    }

    public function deleteMenu($id = null)
    {
        $id = (int) $id;
        $menu = $this->menuModel->find($id);

        if (! $menu) {
            return redirect()->to('/menus')->with('error', 'Menu introuvable.');
        }

        $this->db->transStart();

        $subMenus = $this->sousMenuModel->where('menu_id', $id)->findAll();

        foreach ($subMenus as $subMenu) {
            $this->rolePermissionModel
                ->where('menu_id', $id)
                ->where('sous_menu_id', (int) $subMenu['id'])
                ->delete();

            $this->sousMenuModel->delete((int) $subMenu['id']);
        }

        $this->rolePermissionModel
            ->where('menu_id', $id)
            ->where('sous_menu_id', $id)
            ->delete();

        $this->menuModel->delete($id);

        $this->db->transComplete();

        if (! $this->db->transStatus()) {
            return redirect()->to('/menus')->with('error', 'Impossible de supprimer le menu.');
        }

        return redirect()->to('/menus')->with('success_delete', 'Menu supprimé avec succès.');
    }

    public function saveSubMenu()
    {
        if (! $this->validate($this->subMenuRules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->sousMenuModel->insert($this->subMenuPayload());
        } catch (DatabaseException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Impossible de créer le sous-menu.');
        }

        return redirect()->to('/menus')
            ->with('success', 'Sous-menu créé avec succès.');
    }

    public function updateSubMenu()
    {
        $id = (int) $this->request->getPost('id');

        if ($id <= 0 || ! $this->sousMenuModel->find($id)) {
            return redirect()->to('/menus')->with('error', 'Sous-menu introuvable.');
        }

        if (! $this->validate($this->subMenuRules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        try {
            $this->sousMenuModel->update($id, $this->subMenuPayload());
        } catch (DatabaseException $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Impossible de modifier le sous-menu.');
        }

        return redirect()->to('/menus')
            ->with('success', 'Sous-menu modifié avec succès.');
    }

    public function deleteSubMenu($id = null)
    {
        $id = (int) $id;
        $subMenu = $this->sousMenuModel->find($id);

        if (! $subMenu) {
            return redirect()->to('/menus')->with('error', 'Sous-menu introuvable.');
        }

        $this->db->transStart();

        $this->rolePermissionModel
            ->where('menu_id', (int) $subMenu['menu_id'])
            ->where('sous_menu_id', $id)
            ->delete();

        $this->sousMenuModel->delete($id);

        $this->db->transComplete();

        if (! $this->db->transStatus()) {
            return redirect()->to('/menus')->with('error', 'Impossible de supprimer le sous-menu.');
        }

        return redirect()->to('/menus')->with('success_delete', 'Sous-menu supprimé avec succès.');
    }

    protected function menuRules(): array
    {
        return [
            'nom_menu' => 'required|max_length[100]',
            'icone' => 'permit_empty|max_length[50]',
            'url' => 'permit_empty|max_length[150]',
            'ordre' => 'permit_empty|is_natural',
            'permission_id' => 'required|is_natural_no_zero',
            'statut' => 'required|in_list[0,1]',
        ];
    }

    protected function subMenuRules(): array
    {
        return [
            'menu_id' => 'required|is_natural_no_zero',
            'nom_sous_menu' => 'required|max_length[100]',
            'icon' => 'permit_empty|max_length[50]',
            'url' => 'required|max_length[150]',
            'ordre' => 'permit_empty|is_natural',
            'permission_id' => 'required|is_natural_no_zero',
            'statut' => 'required|in_list[0,1]',
        ];
    }

    protected function menuPayload(): array
    {
        return [
            'nom_menu' => trim((string) $this->request->getPost('nom_menu')),
            'icone' => $this->nullableString($this->request->getPost('icone')),
            'url' => $this->nullableString($this->request->getPost('url')),
            'ordre' => (int) ($this->request->getPost('ordre') ?: 0),
            'permission_id' => (int) $this->request->getPost('permission_id'),
            'statut' => (int) $this->request->getPost('statut'),
        ];
    }

    protected function subMenuPayload(): array
    {
        return [
            'menu_id' => (int) $this->request->getPost('menu_id'),
            'nom_sous_menu' => trim((string) $this->request->getPost('nom_sous_menu')),
            'icon' => $this->nullableString($this->request->getPost('icon')),
            'url' => trim((string) $this->request->getPost('url')),
            'ordre' => (int) ($this->request->getPost('ordre') ?: 0),
            'permission_id' => (int) $this->request->getPost('permission_id'),
            'statut' => (int) $this->request->getPost('statut'),
        ];
    }

    protected function nullableString($value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }
}
