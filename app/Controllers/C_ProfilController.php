<?php

namespace App\Controllers;

use App\Models\M_MenuModel;
use App\Models\M_PermissionModel;
use App\Models\M_RoleModel;
use App\Models\M_SousMenuModel;
use App\Models\M_UserModel;

class C_ProfilController extends BaseController
{
    protected $roleModel;
    protected $menuModel;
    protected $sousMenuModel;
    protected $permissionModel;
    protected $userModel;

    public function __construct()
    {
        $this->roleModel = new M_RoleModel();
        $this->menuModel = new M_MenuModel();
        $this->sousMenuModel = new M_SousMenuModel();
        $this->permissionModel = new M_PermissionModel();
        $this->userModel = new M_UserModel();
    }

    public function index()
    {
        $data['user_permissions'] = $this->getUserPermissions();
        $roles = $this->roleModel->findAll();
        $permissions = $this->permissionModel->findAll();
        $rolePermissions = $this->rolePermissionModel
            ->select('role_id, menu_id, sous_menu_id, permission_id')
            ->findAll();
        $menus = $this->menuModel->findAll();
        $permissionsByRole = [];

        foreach ($rolePermissions as $permission) {
            $permissionsByRole[(int) $permission['role_id']][] = $permission;
        }

        foreach ($menus as &$menu) {
            $menu['sous_menus'] = $this->sousMenuModel
                ->where('menu_id', $menu['id'])
                ->findAll();
        }

        $data['roles'] = $roles;
        $data['menus'] = $menus;
        $data['permissions'] = $permissions;
        $data['role_permissions_by_role'] = $permissionsByRole;

        return view('V_GestionProfil', $data);
    }

    public function save()
    {
        $nom_role = $this->request->getPost('nom_role');
        $permissions = array_values(array_unique($this->request->getPost('permissions') ?? []));

        $this->roleModel->save(['nom_role' => $nom_role]);
        $role_id = $this->roleModel->getInsertID();

        if ($permissions) {
            foreach ($permissions as $perm) {
                [$menu_id, $sous_menu_id, $permission_id] = explode('|', $perm);

                $this->rolePermissionModel->save([
                    'role_id'       => $role_id,
                    'menu_id'       => $menu_id,
                    'sous_menu_id'  => $sous_menu_id,
                    'permission_id' => $permission_id,
                ]);
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Profil crÃ©Ã© avec succÃ¨s',
        ]);
    }

    public function getProfil($id)
    {
        $role = $this->roleModel->find($id);

        $permissions = $this->rolePermissionModel
            ->select('role_id, menu_id, sous_menu_id, permission_id')
            ->where('role_id', $id)
            ->findAll();

        return $this->response->setJSON([
            'role'        => $role,
            'permissions' => $permissions,
        ]);
    }

    public function update()
    {
        $role_id = $this->request->getPost('role_id');
        $nom_role = $this->request->getPost('nom_role');
        $permissions = array_values(array_unique($this->request->getPost('permissions') ?? []));

        $this->roleModel->update($role_id, ['nom_role' => $nom_role]);
        $this->rolePermissionModel->where('role_id', $role_id)->delete();

        if ($permissions) {
            foreach ($permissions as $perm) {
                [$menu_id, $sous_menu_id, $permission_id] = explode('|', $perm);

                $this->rolePermissionModel->insert([
                    'role_id'       => $role_id,
                    'menu_id'       => $menu_id,
                    'sous_menu_id'  => $sous_menu_id,
                    'permission_id' => $permission_id,
                ]);
            }
        }

        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Profil modifiÃ© avec succÃ¨s',
        ]);
    }

    public function delete($id)
    {
        if (!$id) {
            return redirect()->to('/profils')->with('error', 'ID invalide.');
        }

        $usersWithRole = $this->userModel->where('role_id', $id)->findAll();

        if ($usersWithRole) {
            $countUsers = count($usersWithRole);
            $userText = $countUsers > 1 ? 'utilisateurs' : 'utilisateur';

            return redirect()->to('/profils')
                ->with('error', "Impossible de supprimer ce rÃ´le : $countUsers $userText y sont associÃ©s.");
        }

        $this->rolePermissionModel->where('role_id', $id)->delete();
        $deleted = $this->roleModel->delete($id);

        return redirect()->to('/profils')
            ->with('success', $deleted ? 'Profil supprimÃ© avec succÃ¨s.' : 'Impossible de supprimer ce profil.');
    }

    public function delete_ajax($id = null)
    {
        if (!$id) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'ID invalide.',
            ]);
        }

        $usersWithRole = $this->userModel->where('role_id', $id)->findAll();

        if ($usersWithRole) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Impossible de supprimer ce rÃ´le : des utilisateurs y sont associÃ©s.',
            ]);
        }

        $this->rolePermissionModel->where('role_id', $id)->delete();
        $deleted = $this->roleModel->delete($id);

        return $this->response->setJSON([
            'status'  => $deleted ? 'success' : 'error',
            'message' => $deleted ? 'Profil supprimÃ© avec succÃ¨s.' : 'Impossible de supprimer ce profil.',
        ]);
    }
}
