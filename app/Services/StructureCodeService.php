<?php

namespace App\Services;

class StructureCodeService
{
    /**
     * Génère un code unique pour une structure.
     * Format: commence par 4, 10 chiffres, finit par 2.
     * Exemple: 4000000002, 4000000012, etc.
     *
     * @return string
     */
    public function generateCode(): string
    {
        do {
            // Génère un nombre aléatoire entre 00000000 et 99999999
            $random = str_pad(mt_rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            $code = '4' . $random . '2';
        } while ($this->codeExists($code));

        return $code;
    }

    /**
     * Vérifie si le code existe déjà dans la base de données.
     *
     * @param string $code
     * @return bool
     */
    private function codeExists(string $code): bool
    {
        $db = \Config\Database::connect();
        $result = $db->query("SELECT COUNT(*) as count FROM structures WHERE code_structure = ? AND (deleted_at IS NULL OR deleted_at = '')", [$code])->getRow();
        return $result->count > 0;
    }
}