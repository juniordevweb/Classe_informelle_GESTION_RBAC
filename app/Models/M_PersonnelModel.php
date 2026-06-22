<?php

namespace App\Models;

use CodeIgniter\Model;

class M_PersonnelModel extends Model
{
    protected $table = 'personnels';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'prenom',
        'nom',
        'dateNaissance',
        'lieuNaissance',
        'sexe',
        'indicatifPays',
        'numTel',
        'numW',
        'addMail',
        'nationalite',
        'diplome',
        'certificat',
        'categorie_id',
        'cycle_id',
        'id_atlas',
        'zone_residence',
        'situation_handicap',
        'formulaire_complete',
        'completed_at',
    ];

    protected $useTimestamps = true;
}
