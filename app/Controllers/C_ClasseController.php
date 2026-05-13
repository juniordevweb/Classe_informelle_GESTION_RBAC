<?php

namespace App\Controllers;

use App\Models\M_ClasseModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use Config\Database;

class C_ClasseController extends BaseController
{
    protected M_ClasseModel $classeModel;

    public function __construct()
    {
        $this->classeModel = new M_ClasseModel();
    }

    public function index()
    {
        $data['user_permissions'] = $this->getUserPermissions();
        $perPage = 3;
        $data['classes'] = $this->classeModel->orderBy('id', 'DESC')->paginate($perPage, 'classes');
        $data['pager'] = $this->classeModel->pager;
        $data['structures'] = $this->getReferenceOptions('structures');
        $data['facilitateurs'] = $this->getFacilitateurOptions();

        return view('V_GestionClasses', $data);
    }

    public function save()
    {
        $rules = [
            'nom_classe' => 'required|max_length[100]',
            'code_classe' => 'required|max_length[50]|is_unique[classes.code_classe]',
            'structure_id' => 'permit_empty|integer',
            'facilitateur_id' => 'permit_empty|integer',
            'niveau' => 'required|in_list[CI,CP,CE1,CE2]',
            'langue' => 'permit_empty|max_length[50]',
            'date_ouverture' => 'permit_empty|valid_date[Y-m-d]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/classes')
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $data = [
            'nom_classe' => trim((string) $this->request->getPost('nom_classe')),
            'code_classe' => trim((string) $this->request->getPost('code_classe')),
            'structure_id' => $this->normalizeOptionalForeignKey($this->request->getPost('structure_id'), 'structures'),
            'facilitateur_id' => $this->normalizeOptionalForeignKey($this->request->getPost('facilitateur_id'), 'facilitateur'),
            'niveau' => $this->request->getPost('niveau'),
            'langue' => trim((string) $this->request->getPost('langue')),
            'date_ouverture' => $this->request->getPost('date_ouverture') ?: null,
        ];

        try {
            $this->classeModel->insert($data);
        } catch (DatabaseException $e) {
            return redirect()->to('/classes')
                ->withInput()
                ->with('error', 'Impossible de creer la classe.');
        }

        return redirect()->to('/classes')
            ->with('success', 'Classe creee avec succes.');
    }

    public function update()
    {
        $id = (int) $this->request->getPost('id');
        $classe = $this->classeModel->find($id);

        if (! $classe) {
            return redirect()->to('/classes')
                ->with('error', 'Classe introuvable.');
        }

        $codeClasse = trim((string) $this->request->getPost('code_classe'));
        $existing = $this->classeModel
            ->where('code_classe', $codeClasse)
            ->where('id !=', $id)
            ->first();

        if ($existing) {
            return redirect()->to('/classes')
                ->withInput()
                ->with('error', 'Le code de classe existe deja.');
        }

        $rules = [
            'nom_classe' => 'required|max_length[100]',
            'code_classe' => 'required|max_length[50]',
            'structure_id' => 'permit_empty|integer',
            'facilitateur_id' => 'permit_empty|integer',
            'niveau' => 'required|in_list[CI,CP,CE1,CE2]',
            'langue' => 'permit_empty|max_length[50]',
            'date_ouverture' => 'permit_empty|valid_date[Y-m-d]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/classes')
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $data = [
            'nom_classe' => trim((string) $this->request->getPost('nom_classe')),
            'code_classe' => $codeClasse,
            'structure_id' => $this->normalizeOptionalForeignKey($this->request->getPost('structure_id'), 'structures'),
            'facilitateur_id' => $this->normalizeOptionalForeignKey($this->request->getPost('facilitateur_id'), 'facilitateur'),
            'niveau' => $this->request->getPost('niveau'),
            'langue' => trim((string) $this->request->getPost('langue')),
            'date_ouverture' => $this->request->getPost('date_ouverture') ?: null,
        ];

        try {
            $this->classeModel->update($id, $data);
        } catch (DatabaseException $e) {
            return redirect()->to('/classes')
                ->withInput()
                ->with('error', 'Impossible de modifier la classe.');
        }

        return redirect()->to('/classes')
            ->with('success', 'Classe modifiee avec succes.');
    }

    public function delete($id = null)
    {
        $id = (int) $id;
        $classe = $this->classeModel->find($id);

        if (! $classe) {
            return redirect()->to('/classes')
                ->with('error', 'Classe introuvable.');
        }

        $this->classeModel->delete($id);

        return redirect()->to('/classes')
            ->with('success', 'Classe supprimee avec succes.');
    }

    protected function getReferenceOptions(string $tableName): array
    {
        $db = Database::connect();

        if (! $db->tableExists($tableName)) {
            return [];
        }

        $fields = $db->getFieldNames($tableName);
        $labelColumn = $this->resolveLabelColumn($fields);

        if ($labelColumn === null || ! in_array('id', $fields, true)) {
            return [];
        }

        $rows = $db->table($tableName)
            ->select('id, ' . $labelColumn)
            ->orderBy($labelColumn, 'ASC')
            ->get()
            ->getResultArray();

        return array_map(static function (array $row) use ($labelColumn): array {
            return [
                'id' => $row['id'],
                'label' => $row[$labelColumn] ?? ('#' . $row['id']),
            ];
        }, $rows);
    }

    protected function getFacilitateurOptions(): array
    {
        $db = Database::connect();

        if (! $db->tableExists('facilitateur')) {
            return [];
        }

        $rows = $db->table('facilitateur')
            ->select('id, prenom, nom')
            ->orderBy('nom', 'ASC')
            ->orderBy('prenom', 'ASC')
            ->get()
            ->getResultArray();

        return array_map(static function (array $row): array {
            return [
                'id' => $row['id'],
                'label' => trim(sprintf('%s %s', $row['prenom'] ?? '', $row['nom'] ?? '')),
            ];
        }, $rows);
    }

    protected function resolveLabelColumn(array $fields): ?string
    {
        $candidates = ['nom', 'nom_structure', 'libelle', 'designation', 'titre'];

        foreach ($candidates as $candidate) {
            if (in_array($candidate, $fields, true)) {
                return $candidate;
            }
        }

        return null;
    }

    protected function normalizeOptionalForeignKey($value, string $tableName): ?int
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $id = (int) $value;

        if ($id <= 0) {
            return null;
        }

        $db = Database::connect();

        if (! $db->tableExists($tableName)) {
            return null;
        }

        $exists = $db->table($tableName)
            ->where('id', $id)
            ->countAllResults();

        return $exists > 0 ? $id : null;
    }
}
