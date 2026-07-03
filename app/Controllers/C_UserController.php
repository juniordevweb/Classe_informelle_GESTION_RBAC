<?php

namespace App\Controllers;

use App\Models\M_PersonnelModel;
use App\Models\M_RoleModel;
use App\Models\M_UserModel;
use App\Services\UserInvitationService;

class C_UserController extends BaseController
{
    protected $userModel;
    protected $roleModel;
    protected $personnelModel;
    protected UserInvitationService $userInvitationService;

    public function __construct()
    {
        $this->userModel = new M_UserModel();
        $this->roleModel = new M_RoleModel();
        $this->personnelModel = new M_PersonnelModel();
        $this->userInvitationService = service('userInvitationService');
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

    /**
     * Recherche un personnel en deux étapes:
     * 1. d'abord dans la table locale `personnels`
     * 2. puis dans l'API distante si rien n'est trouvé en base
     *
     * La réponse est normalisée au format attendu par la modale
     * d'ajout utilisateur: nom, prenom, ine, email.
     */
    public function search_personnel()
    {
        $search = trim((string) $this->request->getGet('q'));

        if ($search === '') {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Veuillez saisir un IEN ou un email professionnel.',
            ]);
        }

        $normalizedSearch = strtoupper($search);

        // 1) Recherche locale en base: on privilégie la table personnels
        // pour éviter un appel réseau quand l'information existe déjà.
        $localPersonnel = $this->personnelModel
            ->groupStart()
                ->where('id_atlas', $search)
                ->orWhere('addMail', $search)
                ->orWhere('addMail', $normalizedSearch)
            ->groupEnd()
            ->first();

        if ($localPersonnel) {
            return $this->response->setJSON([
                'status' => 'success',
                'source' => 'local',
                'user' => [
                    'nom'    => $localPersonnel['nom'] ?? '',
                    'prenom' => $localPersonnel['prenom'] ?? '',
                    'ine'    => $localPersonnel['id_atlas'] ?? '',
                    'email'  => $localPersonnel['addMail'] ?? '',
                ],
            ]);
        }

        // 2) Fallback réseau: seulement si la base locale ne contient rien.
        try {
            $client = \Config\Services::curlrequest();
            $apiUrl = (string) config('App')->personnelApiUrl;

            if ($apiUrl === '') {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => "L'URL de l'API personnel n'est pas configurée.",
                ]);
            }

            $response = $client->get($apiUrl, [
                'query' => [
                    'ien' => $normalizedSearch,
                ],
                'timeout' => 15,
                'connect_timeout' => 5,
                'http_errors' => false,
            ]);

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode >= 300) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => "L'API de personnel a répondu avec le code HTTP {$statusCode}.",
                    'details' => [
                        'http_code' => $statusCode,
                    ],
                ]);
            }

            $body = $response->getBody();
            $contentType = strtolower((string) $response->getHeaderLine('Content-Type'));

            // Si le service distant renvoie du HTML au lieu du JSON attendu,
            // on considère que l'API n'est pas exploitable pour cette recherche.
            if ($contentType !== '' && !str_contains($contentType, 'application/json')) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Le service distant ne renvoie pas de JSON sur cette URL.',
                    'details' => [
                        'content_type' => $contentType,
                        'http_code' => $statusCode,
                    ],
                ]);
            }

            if (str_starts_with(ltrim($body), '<!doctype html>') || str_starts_with(ltrim($body), '<html')) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Le service distant renvoie une page HTML au lieu de l’API attendue.',
                    'details' => [
                        'http_code' => $statusCode,
                    ],
                ]);
            }

            $data = json_decode($body, true);

            if (
                !is_array($data) ||
                (string) ($data['code'] ?? '') !== '0' ||
                empty($data['personnel']) ||
                empty($data['personnel'][0])
            ) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Aucun personnel trouvé dans la base locale ni via l’API.',
                    'api_response' => $data,
                ]);
            }
  
            $personnel = $data['personnel'][0];

            return $this->response->setJSON([
                'status' => 'success',
                'source' => 'api',
                'user' => [
                    'nom'    => $personnel['nom_pers'] ?? '',
                    'prenom' => $personnel['prenom_pers'] ?? '',
                    'ine'    => $personnel['ien_pers'] ?? '',
                    'email'  => $personnel['email_pro'] ?? '',
                ],
            ]);
        } catch (\Throwable $e) {
            $exceptionMessage = $e->getMessage();
            $isDnsFailure = stripos($exceptionMessage, 'Could not resolve host') !== false
                || (int) $e->getCode() === 6;

            log_message('error', 'User search API connection failed: {message}', [
                'message' => $exceptionMessage,
            ]);

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $isDnsFailure
                    ? "Impossible de joindre l'API de personnel depuis ce serveur."
                    : "Erreur lors de la connexion avec l'API de personnel.",
               // 'details' => $exceptionMessage,
               // 'api_url' => $apiUrl ?? null,
               
            ]);
        }
    }
    public function save_user()
    {
        $rules = [
            'nom' => 'required|max_length[100]',
            'prenom' => 'required|max_length[100]',
            'ine' => 'permit_empty|max_length[100]',
            'email' => 'required|valid_email|max_length[190]',
            'role_id' => 'required|is_natural_no_zero',
            'password' => 'required|min_length[8]|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $nom = trim((string) $this->request->getPost('nom'));
        $prenom = trim((string) $this->request->getPost('prenom'));
        $ine = trim((string) $this->request->getPost('ine'));
        $email = trim((string) $this->request->getPost('email'));
        $roleId = (int) $this->request->getPost('role_id');
        $rawPassword = trim((string) $this->request->getPost('password'));
        $sendEmail = $this->request->getPost('submit_action') === 'save_and_send';

        $existingUser = $this->userModel
            ->where('email', $email)
            ->first();

        if ($existingUser) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Cet utilisateur existe déjà.');
        }

        $userData = [
            'nom' => $nom,
            'email' => $email,
            'password' => password_hash($rawPassword, PASSWORD_DEFAULT),
            'role_id' => $roleId,
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

        try {
            $result = $this->userModel->insert($userData, true);

            if ($result === false) {
                throw new \RuntimeException('Impossible d’enregistrer l’utilisateur.');
            }
        } catch (\Throwable $e) {
            log_message('error', 'User creation flow failed: {message}', [
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Impossible d’enregistrer l’utilisateur.');
        }

        $emailSendFailed = false;
        $emailSendError = '';

        if ($sendEmail) {
            try {
                $this->userInvitationService->sendWelcomeEmail([
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                ], $rawPassword);
            } catch (\Throwable $e) {
                $emailSendFailed = true;
                $emailSendError = $e->getMessage();

               log_message('error', 'Welcome email could not be sent: {message}', [
                  'message' => $emailSendError,
               ]);
            }
        }

        return redirect()->to('/users')
            ->with('success', $sendEmail
                ? ($emailSendFailed
                    ? 'Utilisateur créé avec succès, mais l’envoi de l’e-mail a échoué.'
                    : 'Utilisateur créé et e-mail envoyé avec succès dans Mailpit.')
                : 'Utilisateur créé avec succès.');
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
