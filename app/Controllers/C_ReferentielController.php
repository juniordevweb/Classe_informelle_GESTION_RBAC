<?php

namespace App\Controllers;

use CodeIgniter\Database\BaseConnection;

/**
 * CRUD commun aux référentiels de configuration des structures.
 */
class C_ReferentielController extends BaseController
{
    private ?string $validationError = null;

    private array $referentiels = [
        'types-offre' => [
            'title' => "Types d'offre", 'table' => 'types_offre', 'label' => 'libelle',
            'fields' => ['libelle', 'code', 'prefixe', 'description', 'actif'],
            'labels' => ['libelle' => 'Libellé', 'code' => 'Code', 'prefixe' => 'Préfixe', 'description' => 'Description', 'actif' => 'Actif'],
            'types' => ['code' => 'text'],
        ],
        'langues' => [
            'title' => 'Langues nationales', 'table' => 'langues_nationales', 'label' => 'nom',
            'fields' => ['nom', 'code', 'actif'],
            'labels' => ['nom' => 'Nom', 'code' => 'Code', 'actif' => 'Actif'],
        ],
        'sites' => [
            'title' => 'Sites abritants', 'table' => 'sites_structure', 'label' => 'libelle',
            'fields' => ['libelle', 'description', 'actif'],
            'labels' => ['libelle' => 'Libellé', 'description' => 'Description', 'actif' => 'Actif'],
        ],
        'statuts' => [
            'title' => "Statuts d'occupation", 'table' => 'statuts_occupation', 'label' => 'libelle',
            'fields' => ['libelle', 'description', 'actif'],
            'labels' => ['libelle' => 'Libellé', 'description' => 'Description', 'actif' => 'Actif'],
        ],
        'financements' => [
            'title' => 'Sources de financement', 'table' => 'sources_financement', 'label' => 'libelle',
            'fields' => ['libelle', 'description', 'actif'],
            'labels' => ['libelle' => 'Libellé', 'description' => 'Description', 'actif' => 'Actif'],
        ],
        'programmes' => [
            'title' => 'Programmes', 'table' => 'programmes', 'label' => 'nom',
            'fields' => ['nom', 'description', 'actif'],
            'labels' => ['nom' => 'Nom', 'description' => 'Description', 'actif' => 'Actif'],
        ],
        'etats' => [
            'title' => 'États des structures', 'table' => 'etat_structures', 'label' => 'libelle',
            'fields' => ['libelle', 'description', 'actif'],
            'labels' => ['libelle' => 'Libellé', 'description' => 'Description', 'actif' => 'Actif'],
        ],
        'nomenclature' => [
            'title' => 'Nomenclature', 'table' => 'nomenclatures', 'label' => 'nom_site',
            'fields' => ['type_offre_id', 'code', 'nom_site'],
            'labels' => ['type_offre_id' => "Type d'offre", 'code' => "Code de l'offre", 'nom_site' => 'Nom du site'],
        ],
        'codification' => [
            'title' => 'Codification', 'table' => 'regles_codification', 'label' => 'libelle',
            'fields' => ['type_offre_id', 'nom_site', 'code', 'numero_ordre', 'description', 'actif'],
            'labels' => ['type_offre_id' => "Type d'offre", 'numero_ordre' => "Numéro d'ordre", 'code' => 'Code de classe', 'nom_site' => 'Nom du site', 'description' => 'Description', 'actif' => 'Actif'],
        ],
    ];

    public function index(string $slug)
    {
        $config = $this->config($slug);
        $userPermissions = $this->getUserPermissions();
        $configuration = $this->db()->table('menus')
            ->select('id')
            ->where('nom_menu', 'Configuration')
            ->where('statut', 1)
            ->get()
            ->getRowArray();
        $configurationId = (int) ($configuration['id'] ?? 0);
        $subMenu = $this->db()->table('sous_menus')
            ->select('id')
            ->where('menu_id', $configurationId)
            ->where('url', '/configuration/' . $slug)
            ->where('statut', 1)
            ->get()
            ->getRowArray();
        $subMenuId = (int) ($subMenu['id'] ?? 0);
        $hasPermission = static function (array $permissions, int $menuId, int $subMenuId, int $permissionId): bool {
            foreach ($permissions as $permission) {
                if (
                    (int) ($permission['menu_id'] ?? 0) === $menuId &&
                    (int) ($permission['sous_menu_id'] ?? 0) === $subMenuId &&
                    (int) ($permission['permission_id'] ?? 0) === $permissionId
                ) {
                    return true;
                }
            }

            return false;
        };
        $pagerLinks = '';
        $referentielPage = 1;
        $referentielPageCount = 1;
        if (isset($config['table'])) {
            $perPage = 6;
            $page = max(1, (int) ($this->request->getGet('page_referentiel') ?? 1));
            $totalItems = $this->db()->table($config['table'])->countAllResults();
            $referentielPageCount = max(1, (int) ceil($totalItems / $perPage));
            $page = min($page, $referentielPageCount);
            $referentielPage = $page;
            $items = $this->db()->table($config['table'])
                ->orderBy('id', 'DESC')
                ->limit($perPage, ($page - 1) * $perPage)
                ->get()
                ->getResultArray();
            $pagerLinks = service('pager')->makeLinks($page, $perPage, $totalItems, 'arrows_only', 0, 'referentiel');
        } else {
            $items = $this->db()->table($config['table'])->orderBy('id', 'DESC')->get()->getResultArray();
        }

        $offerTypes = [];
        $nextCodes = [];
        $codificationItems = [];
        $sites = [];
        $nextOrders = [];
        if ($slug === 'codification' || $slug === 'nomenclature') {
            $offerTypes = $this->db()->table('types_offre')
                ->select('id, libelle, code')
                ->orderBy('libelle', 'ASC')
                ->get()->getResultArray();

            foreach ($offerTypes as $offerType) {
                $suffix = $this->offerCodeSuffixFromCode((string) ($offerType['code'] ?? ''));
                if ($suffix !== null) {
                    $nextCodes[(int) $offerType['id']] = $this->nextCodificationCode($suffix);
                }
            }

            if ($slug === 'codification') {
                $codificationItems = $this->db()->table('regles_codification')
                    ->orderBy('id', 'DESC')
                    ->get()->getResultArray();
                $sites = $this->db()->table('nomenclatures')
                    ->select('id, nom_site AS libelle, type_offre_id')
                    ->where('nom_site IS NOT NULL')
                    ->where('nom_site !=', '')
                    ->orderBy('libelle', 'ASC')
                    ->get()->getResultArray();
                foreach ($offerTypes as $offerType) {
                    $lastOrder = $this->db()->table('regles_codification')
                        ->selectMax('numero_ordre', 'last_order')
                        ->where('type_offre_id', (int) $offerType['id'])
                        ->get()->getRowArray();
                    $nextOrders[(int) $offerType['id']] = ((int) ($lastOrder['last_order'] ?? 0)) + 1;
                }
            }
        }

        return view('V_Referentiel', [
            'user_permissions' => $userPermissions,
            'config' => $config,
            'slug' => $slug,
            'items' => $items,
            'pagerLinks' => $pagerLinks,
            'referentielPage' => $referentielPage,
            'referentielPageCount' => $referentielPageCount,
            'offerTypes' => $offerTypes,
            'nextCodes' => $nextCodes,
            'codificationItems' => $codificationItems,
            'sites' => $sites,
            'nextOrders' => $nextOrders,
            'canAddReferentiel' => $hasPermission($userPermissions, $configurationId, $subMenuId, 2),
            'canEditReferentiel' => $hasPermission($userPermissions, $configurationId, $subMenuId, 3),
            'canDeleteReferentiel' => $hasPermission($userPermissions, $configurationId, $subMenuId, 4),
        ]);
    }

    public function store(string $slug)
    {
        $config = $this->config($slug);
        $payload = $this->payload($config);

        if ($payload === false) {
            return redirect()->back()->withInput()->with('error', $this->payloadError($config));
        }

        $this->db()->table($config['table'])->insert($payload);

        return redirect()->to('configuration/' . $slug)->with('success', $config['title'] . ' ajouté(e) avec succès.');
    }

    public function update(string $slug, int $id)
    {
        $config = $this->config($slug);
        $payload = $this->payload($config, $id);

        if ($payload === false) {
            return redirect()->back()->withInput()->with('error', $this->payloadError($config));
        }

        $this->db()->table($config['table'])->where('id', $id)->update($payload);

        return redirect()->to('configuration/' . $slug)->with('success', $config['title'] . ' modifié(e) avec succès.');
    }

    public function delete(string $slug, int $id)
    {
        $config = $this->config($slug);
        $this->db()->table($config['table'])->where('id', $id)->delete();

        return redirect()->to('configuration/' . $slug)->with('success', $config['title'] . ' supprimé(e) avec succès.');
    }

    private function config(string $slug): array
    {
        if (! isset($this->referentiels[$slug])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Référentiel introuvable.');
        }

        return $this->referentiels[$slug];
    }

    private function payload(array $config, ?int $id = null): array|false
    {
        $this->validationError = null;
        $payload = [];

        foreach ($config['fields'] as $field) {
            if ($field === 'actif') {
                $payload[$field] = $this->request->getPost($field) ? 1 : 0;
                continue;
            }

            $value = trim((string) $this->request->getPost($field));
            if ($field === $config['label'] && $value === '') {
                return false;
            }
            $payload[$field] = $value === '' ? null : $value;
        }

        if (($config['table'] ?? null) === 'nomenclatures') {
            $typeId = (int) ($payload['type_offre_id'] ?? 0);
            $offer = $this->db()->table('types_offre')->select('id, code, libelle')->where('id', $typeId)->get()->getRowArray();
            $suffix = $this->offerCodeSuffix((string) ($offer['libelle'] ?? ''))
                ?? $this->offerCodeSuffixFromCode((string) ($offer['code'] ?? ''));
            if (! $offer || $suffix === null || $suffix > 4 || trim((string) ($payload['nom_site'] ?? '')) === '') {
                return false;
            }
            $payload['code'] = $this->nextNomenclatureCode($suffix, $id);
            $payload['libelle'] = trim((string) $payload['nom_site']);
        }

        if (($config['table'] ?? null) === 'regles_codification') {
            $typeId = (int) ($payload['type_offre_id'] ?? 0);
            $offer = $this->db()->table('types_offre')->select('code')->where('id', $typeId)->get()->getRowArray();
            $suffix = $this->offerCodeSuffixFromCode((string) ($offer['code'] ?? ''));
            if (! $offer || $suffix === null || trim((string) ($payload['nom_site'] ?? '')) === '') {
                return false;
            }

            $payload['libelle'] = trim((string) $payload['nom_site']);
            $payload['numero_ordre'] = $this->nextCodificationOrder($typeId, $id);
            $payload['code'] = (string) $offer['code'];
            // Les champs génériques d'une règle ne sont pas nécessaires ici.
            $payload['prefixe'] = '4';
            $payload['format'] = '4XXXXXXXX' . $suffix;
        }

        if (($config['table'] ?? null) === 'types_offre') {
            $label = trim((string) ($payload['libelle'] ?? ''));
            $prefix = trim((string) ($payload['prefixe'] ?? ''));

            if (! $this->startsWithUppercase($label)) {
                $this->validationError = 'Le libellé doit commencer par une majuscule.';
                return false;
            }

            if ($prefix !== '' && ! $this->startsWithUppercase($prefix)) {
                $this->validationError = 'Le préfixe doit commencer par une majuscule.';
                return false;
            }

            $existing = $this->db()->table('types_offre')
                ->select('id, libelle')
                ->get()
                ->getResultArray();
            foreach ($existing as $item) {
                if ($id !== null && (int) $item['id'] === $id) {
                    continue;
                }

                if ($this->normalizeForComparison((string) $item['libelle']) === $this->normalizeForComparison($label)) {
                    $this->validationError = 'Ce libellé existe déjà pour un autre type d’offre.';
                    return false;
                }
            }

            try {
                $suffix = $this->offerCodeSuffix((string) ($payload['libelle'] ?? ''))
                    ?? $this->existingOfferCodeSuffix($id);
            } catch (\RuntimeException $exception) {
                $suffix = null;
            }

            if ($suffix === null || $suffix < 1 || $suffix > 4) {
                $this->validationError = 'Le type d’offre doit correspondre à CAF, ECB, Classe Passerelle ou Daara.';
                return false;
            }

            // Le code envoyé par le formulaire et l'ancien code sont toujours
            // ignorés : chaque enregistrement reçoit un code conforme généré ici.
            $payload['code'] = $this->nextOfferCode($suffix, $id);
        }

        return $payload;
    }

    private function payloadError(array $config): string
    {
        if ($this->validationError !== null) {
            return $this->validationError;
        }

        if (($config['table'] ?? null) === 'regles_codification') {
            return "Le type d'offre et le nom du site sont obligatoires, et le code est généré automatiquement.";
        }

        if (($config['table'] ?? null) !== 'types_offre') {
            return 'Le libellé est obligatoire.';
        }

        return "Le code de l'offre est généré automatiquement avec le préfixe 4 et le suffixe correspondant à l'offre.";
    }

    private function startsWithUppercase(string $value): bool
    {
        return $value !== '' && preg_match('/^\\p{Lu}/u', $value) === 1;
    }

    private function normalizeForComparison(string $value): string
    {
        return function_exists('mb_strtolower')
            ? mb_strtolower(trim($value), 'UTF-8')
            : strtolower(trim($value));
    }

    private function nextCodificationCode(int $suffix, ?int $excludeId = null): string
    {
        $query = $this->db()->table('regles_codification')->select('id, code');
        if ($excludeId !== null) {
            $query->where('id !=', $excludeId);
        }

        $maxSequence = -1;
        foreach ($query->get()->getResultArray() as $item) {
            if (preg_match('/^4(\d{8})' . $suffix . '$/', (string) ($item['code'] ?? ''), $matches) === 1) {
                $maxSequence = max($maxSequence, (int) $matches[1]);
            }
        }

        $sequence = $maxSequence + 1;
        if ($sequence > 99999999) {
            throw new \RuntimeException('Le nombre maximal de codes disponibles pour cette offre est atteint.');
        }

        return '4' . str_pad((string) $sequence, 8, '0', STR_PAD_LEFT) . $suffix;
    }

    private function nextNomenclatureCode(int $suffix, ?int $excludeId = null): string
    {
        if ($suffix < 1 || $suffix > 4) {
            throw new \InvalidArgumentException('Le suffixe de la nomenclature doit être compris entre 1 et 4.');
        }

        $query = $this->db()->table('nomenclatures')->select('code');
        if ($excludeId !== null) {
            $query->where('id !=', $excludeId);
        }

        $usedCodes = array_fill_keys(
            array_map(
                static fn (array $item): string => (string) ($item['code'] ?? ''),
                $query->get()->getResultArray()
            ),
            true
        );

        do {
            $middle = str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
            $code = '4' . $middle . (string) $suffix;
        } while (isset($usedCodes[$code]));

        return $code;
    }

    private function nextCodificationOrder(int $typeId, ?int $excludeId = null): int
    {
        $query = $this->db()->table('regles_codification')->selectMax('numero_ordre', 'last_order')->where('type_offre_id', $typeId);
        if ($excludeId !== null) {
            $query->where('id !=', $excludeId);
        }

        return ((int) ($query->get()->getRowArray()['last_order'] ?? 0)) + 1;
    }

    private function offerCodeSuffixFromCode(string $code): ?int
    {
        return preg_match('/^4\d{8}([1-9])$/', $code, $matches) === 1
            ? (int) $matches[1]
            : null;
    }

    private function nextOfferCode(int $suffix, ?int $excludeId = null): string
    {
        if ($suffix < 1 || $suffix > 4) {
            throw new \InvalidArgumentException('Le suffixe du code de l’offre doit être compris entre 1 et 4.');
        }

        $query = $this->db()->table('types_offre')->select('code');
        if ($excludeId !== null) {
            $query->where('id !=', $excludeId);
        }

        $usedCodes = array_fill_keys(
            array_map(
                static fn (array $item): string => (string) ($item['code'] ?? ''),
                $query->get()->getResultArray()
            ),
            true
        );

        do {
            $middle = str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
            $code = '4' . $middle . (string) $suffix;
        } while (isset($usedCodes[$code]));

        return $code;
    }

    private function nextOfferSuffix(?int $excludeId = null): int
    {
        $query = $this->db()->table('types_offre')->select('id, code');
        if ($excludeId !== null) {
            $query->where('id !=', $excludeId);
        }

        $usedSuffixes = [];
        foreach ($query->get()->getResultArray() as $item) {
            if (preg_match('/^4\d{8}([1-9])$/', (string) ($item['code'] ?? ''), $matches) === 1) {
                $usedSuffixes[(int) $matches[1]] = true;
            }
        }

        foreach (range(1, 9) as $suffix) {
            if (! isset($usedSuffixes[$suffix])) {
                return $suffix;
            }
        }

        throw new \RuntimeException('Le suffixe de l’offre doit être identifié parmi 1 et 9.');
    }

    private function existingOfferCodeSuffix(?int $id): ?int
    {
        if ($id === null) {
            return null;
        }

        $item = $this->db()->table('types_offre')->select('code')->where('id', $id)->get()->getRowArray();
        $code = (string) ($item['code'] ?? '');

        return preg_match('/^4\d{8}([1-9])$/', $code, $matches) === 1
            ? (int) $matches[1]
            : null;
    }

    private function offerCodeSuffix(string $label): ?int
    {
        $label = strtoupper(strtr(trim($label), [
            'À' => 'A', 'Â' => 'A', 'Ä' => 'A', 'Ç' => 'C', 'É' => 'E',
            'È' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Î' => 'I', 'Ï' => 'I',
            'Ô' => 'O', 'Ö' => 'O', 'Ù' => 'U', 'Û' => 'U', 'Ü' => 'U',
        ]));

        return match (true) {
            str_contains($label, 'CAF') => 1,
            str_contains($label, 'ECB') => 2,
            str_contains($label, 'PASSAREL') || str_contains($label, 'PASSEREL') => 3,
            str_contains($label, 'DAARA') => 4,
            default => null,
        };
    }

    private function db(): BaseConnection
    {
        return db_connect();
    }
}
