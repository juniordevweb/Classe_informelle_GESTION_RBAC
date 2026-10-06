<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixOfferCodeSuffixes extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('types_offre')) {
            return;
        }

        $suffixes = [
            'CAF' => 1,
            'ECB' => 2,
            'PASSAREL' => 3,
            'PASSEREL' => 3,
            'DAARA' => 4,
        ];

        foreach ($this->db->table('types_offre')->select('id, libelle, code')->get()->getResultArray() as $offer) {
            $label = strtoupper((string) ($offer['libelle'] ?? ''));
            $suffix = null;
            foreach ($suffixes as $needle => $value) {
                if (str_contains($label, $needle)) {
                    $suffix = $value;
                    break;
                }
            }

            if ($suffix === null || ! preg_match('/^\d{10}$/', (string) ($offer['code'] ?? ''))) {
                continue;
            }

            $this->db->table('types_offre')
                ->where('id', (int) $offer['id'])
                ->update(['code' => substr((string) $offer['code'], 0, 9) . $suffix]);
        }
    }

    public function down()
    {
        // Les suffixes corrigés ne doivent pas être restaurés.
    }
}
