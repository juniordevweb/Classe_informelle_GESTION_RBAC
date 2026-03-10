<?php namespace App\Controllers;

use App\Models\M_UserModel;
use App\Models\M_RoleModel;
use App\Models\M_MenuModel;
use App\Models\M_PermissionModel;
use App\Models\M_RolePermissionModel;

class C_ProfilController extends BaseController
{
    protected $roleModel;
    protected $menuModel;
    protected $permissionModel;
    protected $rolePermissionModel;
     protected $userModel;

    public function __construct()
    {
        $this->roleModel = new M_RoleModel();
        $this->menuModel = new M_MenuModel();
        $this->permissionModel = new M_PermissionModel();
        $this->rolePermissionModel = new M_RolePermissionModel();
        $this->userModel = new M_UserModel();
    }

    public function index()
    {
        $data['roles'] = $this->roleModel->findAll();
        $data['menus'] = $this->menuModel->findAll();
        $data['permissions'] = $this->permissionModel->findAll();

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
                list($menu_id, $permission_id) = explode('|', $perm);

                $this->rolePermissionModel->save([
                    'role_id' => $role_id,
                    'menu_id' => $menu_id,
                    'permission_id' => $permission_id
                ]);
            }
        }

        return redirect()->to('/profils')->with('success','Profil créé avec permissions');
    }

    // Récupérer un profil pour le modifier
   public function getProfil($id)
{
    $role = $this->roleModel->find($id); // info du rôle

    $permissions = $this->rolePermissionModel
                        ->where('role_id', $id)
                        ->findAll(); // permissions existantes

    // Retour JSON
    return $this->response->setJSON([
        'role' => $role,
        'permissions' => $permissions
    ]);
}

    // Modifier profil
public function update()
{
    $role_id = $this->request->getPost('role_id');
    $nom_role = $this->request->getPost('nom_role');
    $permissions = $this->request->getPost('permissions');

    $this->roleModel->update($role_id, [
        'nom_role' => $nom_role
    ]);

    $this->rolePermissionModel->where('role_id', $role_id)->delete();

    if($permissions){
        foreach($permissions as $perm){
            list($menu_id, $permission_id) = explode('|', $perm);

            $this->rolePermissionModel->insert([
                'role_id' => $role_id,
                'menu_id' => $menu_id,
                'permission_id' => $permission_id
            ]);
        }
    }

    // 🔥 IMPORTANT
    return redirect()->to('/profils')
                     ->with('success_update', 'Profil modifié avec succès');
}

    // Supprimer profil
  public function delete($id)
{
    if (!$id) {
        return redirect()->to('/profils')->with('error', 'ID invalide.');
    }

    // Vérifier si des utilisateurs utilisent ce rôle
    $usersWithRole = $this->userModel->where('role_id', $id)->findAll();

    if ($usersWithRole) {
        $countUsers = count($usersWithRole);
        $userText = $countUsers > 1 ? 'utilisateurs' : 'utilisateur';

        return redirect()->to('/profils')
            ->with('error', "Impossible de supprimer ce rôle : $countUsers $userText y sont associés.");
    }

    // Supprimer toutes les permissions liées d'abord
    $this->rolePermissionModel->where('role_id', $id)->delete();

    // Supprimer le profil
    $deleted = $this->roleModel->delete($id);

    if ($deleted) {
        return redirect()->to('/profils')->with('success', 'Profil supprimé avec succès.');
    } else {
        return redirect()->to('/profils')->with('error', 'Impossible de supprimer ce profil.');
    }
}

//SUPP CONFiRMATION PROFIL -> USER
public function delete_ajax($id = null)
{
    if (!$id) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'ID invalide.'
        ]);
    }

    $this->userModel = new \App\Models\M_UserModel();
    $usersWithRole = $this->userModel->where('role_id', $id)->findAll();

    if($usersWithRole){
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Impossible de supprimer ce rôle : des utilisateurs y sont associés.'
        ]);
    }

    $this->rolePermissionModel->where('role_id', $id)->delete();
    $deleted = $this->roleModel->delete($id);

    if($deleted){
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Profil supprimé avec succès.'
        ]);
    } else {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Impossible de supprimer ce profil.'
        ]);
    }
}
}