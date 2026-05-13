<?php

namespace App\Models;

use CodeIgniter\Model;

class M_StructureModel extends Model
{
    protected $table = 'structures';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'code_structure',
        'nom_structure',
        'region',
        'departement',
        'commune',
        'quartier',
        'ia',
        'ief',
        'latitude',
        'longitude',
        'langue_nationale',
        'operateur_id',
        'etat',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'code_structure' => 'required|is_unique[structures.code_structure]|exact_length[10]',
        'nom_structure' => 'required|max_length[255]',
        'region' => 'required|max_length[255]',
        'departement' => 'required|max_length[255]',
        'commune' => 'required|max_length[255]',
        'quartier' => 'required|max_length[255]',
        'ia' => 'required|max_length[255]',
        'ief' => 'required|max_length[255]',
        'latitude' => 'permit_empty|decimal',
        'longitude' => 'permit_empty|decimal',
        'langue_nationale' => 'required|max_length[255]',
        'operateur_id' => 'required|integer',
        'etat' => 'required|in_list[EN_ATTENTE,VALIDE,OUVERT,FERME,GELE]',
    ];

    protected $validationMessages = [
        'code_structure' => [
            'required' => 'Le code de la structure est requis.',
            'is_unique' => 'Ce code de structure existe déjà.',
            'exact_length' => 'Le code de structure doit contenir exactement 10 caractères.',
        ],
        'nom_structure' => [
            'required' => 'Le nom de la structure est requis.',
            'max_length' => 'Le nom de la structure ne peut pas dépasser 255 caractères.',
        ],
        // Add more messages as needed
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    // Relations (CodeIgniter 4 n'a pas de relations natives, simples méthodes helper)
    public function operateur()
    {
        // Retourne les opérateurs liés
        $operateurModel = new M_OperateurModel();
        return $operateurModel;
    }

    public function classes()
    {
        // Retourne les classes liées
        $classeModel = new M_ClasseModel();
        return $classeModel;
    }

    // Custom methods
    public function getWithCounts()
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT structures.*, 
                   COUNT(DISTINCT classes.id) as nombre_classes
            FROM structures
            LEFT JOIN classes ON classes.structure_id = structures.id
            GROUP BY structures.id
        ")->getResultArray();
    }
}