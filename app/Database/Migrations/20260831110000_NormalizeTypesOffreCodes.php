<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NormalizeTypesOffreCodes extends Migration
{
    public function up()
    {
        // TINYINT plafonne les codes à 127 et transforme donc 401 en 127.
        $this->db->query("ALTER TABLE types_offre MODIFY code INT UNSIGNED NOT NULL");

        // Les anciens codes peuvent entrer en conflit avec les nouveaux codes.
        // On passe d'abord par des valeurs temporaires uniques, puis on conserve
        // le suffixe historique quand il est compris entre 1 et 4.
        $this->db->query("UPDATE types_offre SET code = 2000000000 + id");

        $rows = $this->db->table('types_offre')->select('id, libelle')->orderBy('id', 'ASC')->get()->getResultArray();
        foreach ($rows as $row) {
            // Correspondance des offres, indépendante de leur identifiant en base.
            $label = strtoupper(strtr(trim((string) $row['libelle']), [
                'À' => 'A', 'Â' => 'A', 'Ä' => 'A', 'Ç' => 'C', 'É' => 'E',
                'È' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Î' => 'I', 'Ï' => 'I',
                'Ô' => 'O', 'Ö' => 'O', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U',
            ]));
            $suffix = str_contains($label, 'CAF') ? 1
                : (str_contains($label, 'ECB') ? 2
                : ((str_contains($label, 'PASSAREL') || str_contains($label, 'PASSERELLE')) ? 3
                : (str_contains($label, 'DAARA') ? 4 : null)));

            if ($suffix === null) {
                continue;
            }

            $code = '4' . str_pad((string) ((int) $row['id']), 8, '0', STR_PAD_LEFT) . $suffix;
            $this->db->table('types_offre')->where('id', (int) $row['id'])->update(['code' => $code]);
        }
    }

    public function down()
    {
        // Les valeurs précédentes ne sont pas restaurées afin de ne pas
        // réintroduire des codes non conformes.
    }
}
