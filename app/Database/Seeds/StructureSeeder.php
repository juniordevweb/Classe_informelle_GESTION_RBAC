<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Services\StructureCodeService;

class StructureSeeder extends Seeder
{
    public function run()
    {
        $codeService = new StructureCodeService();

        $structures = [
            [
                'code_structure' => $codeService->generateCode(),
                'nom_structure' => 'Centre Passerelle Dakar',
                'region' => 'Dakar',
                'departement' => 'Dakar',
                'commune' => 'Dakar',
                'quartier' => 'Plateau',
                'ia' => 'IA Dakar',
                'ief' => 'IEF Plateau',
                'latitude' => 14.6937,
                'longitude' => -17.4441,
                'langue_nationale' => 'Wolof',
                'operateur_id' => 1, // Assuming operateur with id 1 exists
                'etat' => 'OUVERT',
            ],
            [
                'code_structure' => $codeService->generateCode(),
                'nom_structure' => 'Centre Passerelle Thiès',
                'region' => 'Thiès',
                'departement' => 'Thiès',
                'commune' => 'Thiès',
                'quartier' => 'Centre',
                'ia' => 'IA Thiès',
                'ief' => 'IEF Centre',
                'latitude' => 14.7910,
                'longitude' => -16.9359,
                'langue_nationale' => 'Wolof',
                'operateur_id' => 1,
                'etat' => 'VALIDE',
            ],
            [
                'code_structure' => $codeService->generateCode(),
                'nom_structure' => 'Centre Passerelle Saint-Louis',
                'region' => 'Saint-Louis',
                'departement' => 'Saint-Louis',
                'commune' => 'Saint-Louis',
                'quartier' => 'Centre',
                'ia' => 'IA Saint-Louis',
                'ief' => 'IEF Centre',
                'latitude' => 16.0179,
                'longitude' => -16.4896,
                'langue_nationale' => 'Wolof',
                'operateur_id' => 2, // Assuming another operateur
                'etat' => 'EN_ATTENTE',
            ],
        ];

        $this->db->table('structures')->insertBatch($structures);
    }
}