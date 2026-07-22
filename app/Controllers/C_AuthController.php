<?php

namespace App\Controllers;

use App\Models\M_UserModel;

class C_AuthController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new M_UserModel();
    }

    public function login()
    {
        if (session()->get('logged_in')) {
            if (session()->get('force_password_reset')) {
                return redirect()->to('/password/reset');
            }

            return redirect()->to(getDefaultLandingUrl(session()->get('user_permissions') ?? []));
        }

        return view('V_connexion');
    }

    public function process()
    {
        $email = trim($this->request->getPost('email'));
        $password = trim($this->request->getPost('password'));

        $user = $this->userModel->where('email', $email)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Email ou mot de passe incorrect');
        }

        if ($this->isUserBlocked($user)) {
            return redirect()->back()->with('error', 'Votre compte est bloque.');
        }

        $db = \Config\Database::connect();

        $role = $db->table('roles')
            ->select('nom_role')
            ->where('id', $user['role_id'])
            ->get()
            ->getRowArray();

        $permissions = $this->rolePermissionModel
            ->where('role_id', $user['role_id'])
            ->findAll();

        $roleName = $role ? $role['nom_role'] : 'Invite';

        session()->set([
            'user_id'          => $user['id'],
            'nom'              => $user['nom'],
            'email'            => $user['email'],
            'role_id'          => $user['role_id'],
            'role'             => $roleName,
            'user_permissions' => $permissions,
            'force_password_reset' => (bool) ($user['must_change_password'] ?? 0),
            'logged_in'        => true,
        ]);

        session()->regenerate(true);

        // Snapshot the sidebar at login time so menu changes become visible only after reconnection.
        session()->set('sidebar_menus', getSidebarMenus($permissions));

        if ((bool) ($user['must_change_password'] ?? 0)) {
            return redirect()->to('/password/reset')
                ->with('warning', 'Vous devez changer votre mot de passe initial avant d\'utiliser le système.');
        }

        return redirect()->to(getDefaultLandingUrl($permissions));
        
    }

    public function passwordReset()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (! session()->get('force_password_reset')) {
            return redirect()->to(getDefaultLandingUrl(session()->get('user_permissions') ?? []));
        }

        return view('V_ResetPassword', [
            'userName' => trim((string) (session()->get('nom') ?? 'utilisateur')),
        ]);
    }

    public function passwordResetProcess()
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (! session()->get('force_password_reset')) {
            return redirect()->to(getDefaultLandingUrl(session()->get('user_permissions') ?? []));
        }

        $rules = [
            'current_password' => 'required|min_length[6]|max_length[255]',
            'new_password' => 'required|min_length[8]|max_length[255]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        $messages = [
            'current_password' => [
                'required'   => 'Le mot de passe actuel est obligatoire.',
                'min_length'  => 'Le mot de passe actuel doit contenir au moins 6 caractères.',
                'max_length'  => 'Le mot de passe actuel ne peut pas dépasser 255 caractères.',
            ],
            'new_password' => [
                'required'   => 'Le nouveau mot de passe est obligatoire.',
                'min_length'  => 'Le nouveau mot de passe doit contenir au moins 8 caractères.',
                'max_length'  => 'Le nouveau mot de passe ne peut pas dépasser 255 caractères.',
            ],
            'confirm_password' => [
                'required' => 'La confirmation du mot de passe est obligatoire.',
                'matches'  => 'La confirmation du mot de passe ne correspond pas au nouveau mot de passe.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $currentPassword = trim((string) $this->request->getPost('current_password'));
        $newPassword = trim((string) $this->request->getPost('new_password'));
        $confirmPassword = trim((string) $this->request->getPost('confirm_password'));

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'La confirmation du mot de passe ne correspond pas.');
        }

        $userId = (int) session()->get('user_id');
        $user = $this->userModel->find($userId);

        if (! $user) {
            return redirect()->to('/login')->with('error', 'Compte utilisateur introuvable.');
        }

        if (! password_verify($currentPassword, $user['password'] ?? '')) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Le mot de passe actuel est incorrect.');
        }

        if (password_verify($newPassword, $user['password'] ?? '')) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Le nouveau mot de passe doit être différent de l\'ancien.');
        }

        $payload = [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
        ];

        if ($this->userModel->db->fieldExists('must_change_password', $this->userModel->table)) {
            $payload['must_change_password'] = 0;
        }

        $this->userModel->update($userId, $payload);

        session()->set('force_password_reset', false);
        session()->regenerate(true);

        return redirect()->to(getDefaultLandingUrl(session()->get('user_permissions') ?? []))
            ->with('success', 'Mot de passe modifié avec succès.');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }

    protected function isUserBlocked(array $user): bool
    {
        if (! array_key_exists('status', $user)) {
            return false;
        }

        $status = strtolower(trim((string) $user['status']));

        if ($status === '') {
            return false;
        }

        return in_array($status, ['0', 'inactif', 'inactive', 'bloque', 'bloqué'], true);
    }

}
