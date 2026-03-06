<?php namespace App\Models;

use CodeIgniter\Model;

class M_PermissionModel extends Model
{
    protected $table      = 'permissions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom_permission'];
}