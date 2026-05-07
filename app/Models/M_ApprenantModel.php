<?php

namespace App\Models;

use CodeIgniter\Model;

class M_ApprenantModel extends Model
{
    protected $table = 'apprenants';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nom',
        'prenom',
        'sexe',
        'date_naissance',
        'lieu_naissance',
        'situation',
        'derniere_classe',
        'cause_descolarisation',
        'region',
        'departement',
        'commune',
        'nom_parent',
        'telephone_parent',
        'handicap',
        'situation_familiale',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
