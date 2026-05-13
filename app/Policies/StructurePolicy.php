<?php

namespace App\Policies;

use App\Models\M_UserModel;
use App\Models\M_StructureModel;

class StructurePolicy
{
    protected $userModel;
    protected $rolePermissionModel;

    public function __construct()
    {
        $this->userModel = new M_UserModel();
        $this->rolePermissionModel = new \App\Models\M_RolePermissionModel();
    }

    /**
     * Vérifie si l'utilisateur peut voir les structures.
     *
     * @param array $user
     * @return bool
     */
    public function view(array $user): bool
    {
        return $this->hasPermission($user['role_id'], 'structure.view');
    }

    /**
     * Vérifie si l'utilisateur peut créer une structure.
     *
     * @param array $user
     * @return bool
     */
    public function create(array $user): bool
    {
        return $this->hasPermission($user['role_id'], 'structure.create');
    }

    /**
     * Vérifie si l'utilisateur peut modifier une structure.
     *
     * @param array $user
     * @param M_StructureModel $structure
     * @return bool
     */
    public function update(array $user, M_StructureModel $structure): bool
    {
        return $this->hasPermission($user['role_id'], 'structure.update');
    }

    /**
     * Vérifie si l'utilisateur peut supprimer une structure.
     *
     * @param array $user
     * @param M_StructureModel $structure
     * @return bool
     */
    public function delete(array $user, M_StructureModel $structure): bool
    {
        return $this->hasPermission($user['role_id'], 'structure.delete');
    }

    /**
     * Vérifie si l'utilisateur a une permission spécifique.
     *
     * @param int $roleId
     * @param string $permission
     * @return bool
     */
    private function hasPermission(int $roleId, string $permission): bool
    {
        // Pour une architecture simple, on retourne true si l'utilisateur a un role_id
        // En production, il faudrait vérifier contre une table de permissions nommées
        return $roleId > 0;
    }
}