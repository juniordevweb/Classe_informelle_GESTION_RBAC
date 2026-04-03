<?php

namespace App\Models;

use CodeIgniter\Model;

class M_SousMenuModel extends Model
{
    protected $table = 'sous_menus';
    protected $primaryKey = 'id';
    protected $allowedFields = ['menu_id', 'nom_sous_menu'];
    protected $useTimestamps = true;
}
