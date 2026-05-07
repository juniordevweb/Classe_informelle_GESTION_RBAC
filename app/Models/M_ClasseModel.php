<?php

namespace App\Models;

use CodeIgniter\Model;

class M_ClasseModel extends Model
{
    protected $table = 'classes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nom_classe',
        'code_classe',
        'structure_id',
        'facilitateur_id',
        'niveau',
        'langue',
        'date_ouverture',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
