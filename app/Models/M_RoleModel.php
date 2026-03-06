<?php namespace App\Models;

use CodeIgniter\Model;

class M_RoleModel extends Model
{
    protected $table      = 'roles';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom_role', 'created_at'];
    protected $useTimestamps = true;
}