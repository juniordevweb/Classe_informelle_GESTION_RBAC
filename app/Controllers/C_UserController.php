<?php

namespace App\Controllers;

use App\Models\M_RoleModel;
use App\Models\M_UserModel;

class C_UserController extends BaseController
{
    protected $userModel;
    protected $roleModel;

    public function __construct()
    {
        $this->userModel = new M_UserModel();
        $this->roleModel = new M_RoleModel();
    }

    protected function hasStatusColumn(): bool
    {
        return $this->userModel->db->fieldExists('status', $this->userModel->table);
    }

    protected function hasColumn(string $column): bool
    {
        return $this->userModel->db->fieldExists($column, $this->userModel->table);
    }

    public function index()
    {
        $data['user_permissions'] = $this->getUserPermissions();
        $data['users'] = $this->userModel->findAll();
        $data['profils'] = $this->roleModel->findAll();

        return view('V_GestionUser', $data);
    }

 public function search_personnel()
{
    $search = strtoupper(trim($this->request->getGet('q')));

    if (!$search) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Veuillez saisir un IEN.'
        ]);
    }

    try {
        $client = \Config\Services::curlrequest();

        $response = $client->get('https://apps.education.sn/C_personnel_api/getIEN_info', [
            'query' => [
                'ien' => $search
            ],
            'timeout' => 15,
            'http_errors' => false
        ]);

        $body = $response->getBody();
        $data = json_decode($body, true);

        if (
            !is_array($data) ||
            (string)($data['code'] ?? '') !== '0' ||
            empty($data['personnel']) ||
            empty($data['personnel'][0])
        ) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Aucun personnel trouvé.',
                'api_response' => $data
            ]);
        }

        $personnel = $data['personnel'][0];

        return $this->response->setJSON([
            'status' => 'success',
            'user' => [
                'nom'    => $personnel['nom_pers'] ?? '',
                'prenom' => $personnel['prenom_pers'] ?? '',
                'ine'    => $personnel['ien_pers'] ?? '',
                'email'  => $personnel['email_pro'] ?? ''
            ]
        ]);

    } catch (\Exception $e) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Erreur lors de la connexion avec l’API.',
            'details' => $e->getMessage()
        ]);
    }
}
    public function save_user()
    {
        $nom = $this->request->getPost('nom');
        $prenom = $this->request->getPost('prenom');
        $ine = $this->request->getPost('ine');
        $email = $this->request->getPost('email');
        $role_id = $this->request->getPost('role_id');
        $rawPassword = $this->request->getPost('password');

        if (!$nom || !$email || !$role_id || !$rawPassword) {
            return redirect()->back()
                ->with('error', 'Tous les champs sont obligatoires.');
        }

        $existingUser = $this->userModel
            ->where('email', $email)
            ->first();

        if ($existingUser) {
            return redirect()->back()
                ->with('error', 'Cet utilisateur existe déjà.');
        }

        $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);

      $userData = [
    'nom'      => $nom,
    'prenom'   => $prenom,
    'ine'      => $ine,
    'email'    => $email,
    'password' => $hashedPassword,
    'role_id'  => $role_id,
    'status'   => 1
];

        if ($this->hasColumn('prenom')) {
            $userData['prenom'] = $prenom;
        }

        if ($this->hasColumn('ine')) {
            $userData['ine'] = $ine;
        }

        if ($this->hasStatusColumn()) {
            $userData['status'] = 1;
        }

        $this->userModel->save($userData);

        return redirect()->to('/users')
            ->with('success', "Utilisateur créé. Mot de passe : $rawPassword");
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
            ? "Utilisateur bloqué avec succès."
            : "Utilisateur réactivé avec succès.";

        return redirect()->to('/users')->with('success', $message);
    }

    public function delete($id)
    {
        $this->userModel->delete($id);

        return redirect()->to('/users')
            ->with('success_delete', 'Utilisateur supprimé avec succès !');
    }

//PUBLIC FUNCTION GETALLNAMEBYROLEID($role_id)
//    {

    public function update()
    {
        $id = $this->request->getPost('id');

        $this->userModel->update($id, [
            'nom'     => $this->request->getPost('nom'),
            'email'   => $this->request->getPost('email'),
            'role_id' => $this->request->getPost('role_id'),
            
        ]);
        

        return redirect()->to('/users')
            ->with('success_update', 'Utilisateur modifié avec succès !');
    }
}
// End of C_UserController.php -- so diangoul do am--vso amoul niou yapp la-- sougn la yabbé gua niak fayda
