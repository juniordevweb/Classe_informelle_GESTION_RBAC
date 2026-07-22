<?php

namespace App\Models;

use CodeIgniter\Model;

class M_UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom', 'prenom', 'ine', 'email', 'password', 'role_id', 'status', 'must_change_password', 'created_at'];
    protected $useTimestamps = true;
}
