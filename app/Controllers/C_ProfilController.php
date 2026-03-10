<?php namespace App\Controllers;

use App\Models\M_UserModel;
use App\Models\M_RoleModel;
use App\Models\M_MenuModel;
use App\Models\M_SousMenuModel; // nouveau model
use App\Models\M_PermissionModel;
use App\Models\M_RolePermissionModel;

class C_ProfilController extends BaseController
{
    protected $roleModel;
    protected $menuModel;
    protected $sousMenuModel; // nouveau
    protected $permissionModel;
    protected $rolePermissionModel;
    protected $userModel;

    public function __construct()
    {
        $this->roleModel = new M_RoleModel();
        $this->menuModel = new M_MenuModel();
        $this->sousMenuModel = new M_SousMenuModel(); // initialisation
        $this->permissionModel = new M_PermissionModel();
        $this->rolePermissionModel = new M_RolePermissionModel();
        $this->userModel = new M_UserModel();
    }

    public function index()
    {
        $roles = $this->roleModel->findAll();
        $permissions = $this->permissionModel->findAll();

        // Récupération menus + sous-menus dynamiquement
        $menus = $this->menuModel->findAll();

        foreach($menus as &$menu){
            $menu['sous_menus'] = $this->sousMenuModel
                                      ->where('menu_id', $menu['id'])
                                      ->findAll();
        }

        $data = [
            'roles' => $roles,
            'menus' => $menus,
            'permissions' => $permissions
        ];

        return view('V_GestionProfil', $data);
    }

    public function save()
    {
        $nom_role = $this->request->getPost('nom_role');
        $permissions = $this->request->getPost('permissions');

        $this->roleModel->save(['nom_role' => $nom_role]);
        $role_id = $this->roleModel->getInsertID();

        if($permissions){
            foreach($permissions as $perm){
                list($menu_id, $sous_menu_id, $permission_id) = explode('|', $perm);

                $this->rolePermissionModel->save([
                    'role_id' => $role_id,
                    'menu_id' => $menu_id,
                    'sous_menu_id' => $sous_menu_id,
                    'permission_id' => $permission_id
                ]);
            }
        }

        return redirect()->to('/profils')->with('success','Profil créé avec permissions');
    }

  public function getProfil($id)
{
    $role = $this->roleModel->find($id);

    $permissions = $this->rolePermissionModel
                        ->select('role_id, menu_id, sous_menu_id, permission_id')
                        ->where('role_id', $id)
                        ->findAll();

    return $this->response->setJSON([
        'role' => $role,
        'permissions' => $permissions
    ]);
}

    public function update()
    {
        $role_id = $this->request->getPost('role_id');
        $nom_role = $this->request->getPost('nom_role');
        $permissions = $this->request->getPost('permissions');

        $this->roleModel->update($role_id, ['nom_role' => $nom_role]);

        $this->rolePermissionModel->where('role_id', $role_id)->delete();

        if($permissions){
            foreach($permissions as $perm){
                list($menu_id, $sous_menu_id, $permission_id) = explode('|', $perm);

                $this->rolePermissionModel->insert([
                    'role_id' => $role_id,
                    'menu_id' => $menu_id,
                    'sous_menu_id' => $sous_menu_id,
                    'permission_id' => $permission_id
                ]);
            }
        }

        return redirect()->to('/profils')
                         ->with('success_update', 'Profil modifié avec succès');
    }

    // Supprimer profil
    public function delete($id)
    {
        if (!$id) return redirect()->to('/profils')->with('error', 'ID invalide.');

        $usersWithRole = $this->userModel->where('role_id', $id)->findAll();

        if ($usersWithRole) {
            $countUsers = count($usersWithRole);
            $userText = $countUsers > 1 ? 'utilisateurs' : 'utilisateur';
            return redirect()->to('/profils')
                             ->with('error', "Impossible de supprimer ce rôle : $countUsers $userText y sont associés.");
        }

        $this->rolePermissionModel->where('role_id', $id)->delete();
        $deleted = $this->roleModel->delete($id);

        return redirect()->to('/profils')
                         ->with('success', $deleted ? 'Profil supprimé avec succès.' : 'Impossible de supprimer ce profil.');
    }

    public function delete_ajax($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['status' => 'error','message' => 'ID invalide.']);
        }

        $usersWithRole = $this->userModel->where('role_id', $id)->findAll();
        if($usersWithRole){
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Impossible de supprimer ce rôle : des utilisateurs y sont associés.'
            ]);
        }

        $this->rolePermissionModel->where('role_id', $id)->delete();
        $deleted = $this->roleModel->delete($id);

        return $this->response->setJSON([
            'status' => $deleted ? 'success' : 'error',
            'message' => $deleted ? 'Profil supprimé avec succès.' : 'Impossible de supprimer ce profil.'
        ]);
    }
}