<?php

namespace App\Controllers;

use App\Models\M_RoleModel;
use App\Models\M_UserModel;

class C_UserController extends BaseController
{
    protected $userModel;
    protected $roleModel;

    protected function hasStatusColumn(): bool
    {
        return $this->userModel->db->fieldExists('status', $this->userModel->table);
    }

    public function __construct()
    {
        $this->userModel = new M_UserModel();
        $this->roleModel = new M_RoleModel();
    }

    public function index()
    {
        $data['user_permissions'] = $this->getUserPermissions();
        $data['users'] = $this->userModel->findAll();
        $data['profils'] = $this->roleModel->findAll();

        return view('V_GestionUser', $data);
    }

    public function save_user()
    {
        $nom = $this->request->getPost('nom');
        $email = $this->request->getPost('email');
        $role_id = $this->request->getPost('role_id');

        if (!$nom || !$email || !$role_id) {
            return redirect()->back()
                ->with('error', 'Tous les champs sont obligatoires.');
        }

        $rawPassword = $this->request->getPost('password');
        $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);

        $userData = [
            'nom'      => $nom,
            'email'    => $email,
            'password' => $hashedPassword,
            'role_id'  => $role_id,
        ];

        if ($this->hasStatusColumn()) {
            $userData['status'] = 1;
        }

        $this->userModel->save($userData);

        return redirect()->to('/users')
            ->with('success', "Utilisateur creer. Mot de passe: $rawPassword");
    }

    public function block($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/users')
                ->with('error', 'Utilisateur introuvable.');
        }

        if (!$this->hasStatusColumn()) {
            return redirect()->to('/users')
                ->with('error', "La colonne 'status' n'existe pas dans la table users.");
        }

        $newStatus = ($user['status'] == 1) ? 0 : 1;

        $this->userModel->update($id, [
            'status' => $newStatus,
        ]);

        $message = $newStatus == 0
            ? "Utilisateur bloqué avec succée."
            : "Utilisateur rÃ©activé avec succée.";

        return redirect()->to('/users')->with('success', $message);
    }

    public function delete($id)
    {
        $this->userModel->delete($id);

        return redirect()->to('/users')->with('success_delete', 'Utilisateur supprimé avec succée !');
    }

    public function update()
    {
        $id = $this->request->getPost('id');

        $this->userModel->update($id, [
            'nom'     => $this->request->getPost('nom'),
            'email'   => $this->request->getPost('email'),
            'role_id' => $this->request->getPost('role_id'),
        ]);

        return redirect()->to('/users')->with('success_update', 'Utilisateur modifier avec succée !');
    }
}
