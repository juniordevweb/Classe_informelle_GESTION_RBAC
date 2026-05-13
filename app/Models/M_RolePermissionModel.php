<?php

namespace App\Models;

use CodeIgniter\Model;

class M_RolePermissionModel extends Model
{
    protected $table = 'role_permissions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['role_id', 'menu_id', 'sous_menu_id', 'permission_id'];

    /**
     * Récupère les permissions d'un rôle
     *
     * @param int $roleId
     * @return array
     */
    public function getPermissionsByRole(int $roleId)
    {
        return $this->where('role_id', $roleId)->findAll();
    }
}
