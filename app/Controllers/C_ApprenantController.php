<?php

namespace App\Controllers;

use App\Models\M_ApprenantModel;
use App\Models\M_ClasseModel;
use Config\Database;

class C_ApprenantController extends BaseController
{
    protected M_ApprenantModel $apprenantModel;
    protected M_ClasseModel $classeModel;

    public function __construct()
    {
        $this->apprenantModel = new M_ApprenantModel();
        $this->classeModel = new M_ClasseModel();
    }

    public function index()
    {
        $data['user_permissions'] = $this->getUserPermissions();
        $perPage = 3;
        $selectedClassId = (int) $this->request->getGet('classe_id');
        $data['open_add_modal'] = $this->request->getGet('open_modal') === '1';
        $selectedClassName = null;

        if ($selectedClassId > 0) {
            $classe = $this->classeModel->find($selectedClassId);
            $selectedClassName = $classe ? $classe['nom_classe'] : null;
        }

        $query = $this->apprenantModel->orderBy('nom', 'ASC')->orderBy('prenom', 'ASC');

        if ($selectedClassName) {
            $query = $query->where('derniere_classe', $selectedClassName);
        }

        $data['classes'] = $this->getClasseOptions();
        $data['apprenants'] = $query->paginate($perPage, 'apprenants');
        $data['pager'] = $this->apprenantModel->pager;
        $data['performances'] = $this->buildPerformanceData($data['apprenants'], $this->getApprenantNotes());
        $data['selected_class'] = $selectedClassName;
        $data['selected_class_id'] = $selectedClassId;
        $data['open_detail_id'] = (int) $this->request->getGet('open_detail_id');

        return view('V_GestionApprenant', $data);
    }

    public function saveNote()
    {
        $apprenantId = (int) $this->request->getPost('apprenant_id');
        if ($apprenantId <= 0) {
            return redirect()->to('/apprenant')
                ->with('error', 'Apprenant invalide.');
        }

        $noteIndex = $this->request->getPost('note_index');
        $note = [
            'devoir' => trim((string) $this->request->getPost('devoir')),
            'note1' => trim((string) $this->request->getPost('note1')),
            'note2' => trim((string) $this->request->getPost('note2')),
            'composition' => trim((string) $this->request->getPost('composition')),
            'moyenne' => trim((string) $this->request->getPost('moyenne')),
        ];

        $notes = $this->getApprenantNotes();
        if (is_numeric($noteIndex) && $noteIndex !== '') {
            $index = (int) $noteIndex;
            if (isset($notes[$apprenantId][$index])) {
                $notes[$apprenantId][$index] = $note;
            } else {
                $notes[$apprenantId][] = $note;
            }
        } else {
            $notes[$apprenantId][] = $note;
        }

        $this->setApprenantNotes($notes);

        $redirectUrl = '/apprenant?open_detail_id=' . $apprenantId;
        $selectedClassId = (int) $this->request->getPost('classe_id');
        if ($selectedClassId > 0) {
            $redirectUrl .= '&classe_id=' . $selectedClassId;
        }

        return redirect()->to($redirectUrl)
            ->with('success', 'Note enregistrée.');
    }

    public function deleteNote()
    {
        $apprenantId = (int) $this->request->getPost('apprenant_id');
        $noteIndex = (int) $this->request->getPost('note_index');

        if ($apprenantId <= 0) {
            return redirect()->to('/apprenant')
                ->with('error', 'Apprenant invalide.');
        }

        $notes = $this->getApprenantNotes();
        if (isset($notes[$apprenantId][$noteIndex])) {
            array_splice($notes[$apprenantId], $noteIndex, 1);
            if (empty($notes[$apprenantId])) {
                unset($notes[$apprenantId]);
            }
            $this->setApprenantNotes($notes);
        }

        $redirectUrl = '/apprenant?open_detail_id=' . $apprenantId;
        $selectedClassId = (int) $this->request->getPost('classe_id');
        if ($selectedClassId > 0) {
            $redirectUrl .= '&classe_id=' . $selectedClassId;
        }

        return redirect()->to($redirectUrl)
            ->with('success', 'Note supprimee.');
    }

    protected function getApprenantNotes(): array
    {
        $notes = session()->get('apprenant_notes');
        return is_array($notes) ? $notes : [];
    }

    protected function setApprenantNotes(array $notes): void
    {
        session()->set('apprenant_notes', $notes);
    }

    public function save()
    {
        $rules = [
            'nom' => 'required|max_length[100]',
            'prenom' => 'required|max_length[100]',
            'sexe' => 'required|in_list[M,F]',
            'date_naissance' => 'permit_empty|valid_date[Y-m-d]',
            'lieu_naissance' => 'permit_empty|max_length[150]',
            'situation' => 'permit_empty|in_list[Descolarise,Non_scolarise]',
            'derniere_classe' => 'permit_empty|max_length[100]',
            'cause_descolarisation' => 'permit_empty|max_length[65535]',
            'region' => 'permit_empty|max_length[100]',
            'departement' => 'permit_empty|max_length[100]',
            'commune' => 'permit_empty|max_length[100]',
            'nom_parent' => 'permit_empty|max_length[150]',
            'telephone_parent' => 'permit_empty|max_length[20]',
            'handicap' => 'required|in_list[oui,non]',
            'situation_familiale' => 'permit_empty|max_length[150]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/apprenant')
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $derniereClasse = $this->resolveClasseLabel((int) $this->request->getPost('classe_id')) ?: trim((string) $this->request->getPost('derniere_classe'));

        $this->apprenantModel->insert([
            'nom' => trim((string) $this->request->getPost('nom')),
            'prenom' => trim((string) $this->request->getPost('prenom')),
            'sexe' => $this->request->getPost('sexe'),
            'date_naissance' => $this->request->getPost('date_naissance') ?: null,
            'lieu_naissance' => trim((string) $this->request->getPost('lieu_naissance')),
            'situation' => $this->request->getPost('situation'),
            'derniere_classe' => $derniereClasse,
            'cause_descolarisation' => trim((string) $this->request->getPost('cause_descolarisation')),
            'region' => trim((string) $this->request->getPost('region')),
            'departement' => trim((string) $this->request->getPost('departement')),
            'commune' => trim((string) $this->request->getPost('commune')),
            'nom_parent' => trim((string) $this->request->getPost('nom_parent')),
            'telephone_parent' => trim((string) $this->request->getPost('telephone_parent')),
            'handicap' => $this->request->getPost('handicap'),
            'situation_familiale' => trim((string) $this->request->getPost('situation_familiale')),
        ]);

        return redirect()->to('/apprenant')
            ->with('success', 'Apprenant ajoute avec succes.');
    }

    public function update()
    {
        $id = (int) $this->request->getPost('id');
        $apprenant = $this->apprenantModel->find($id);

        if (! $apprenant) {
            return redirect()->to('/apprenant')
                ->with('error', 'Apprenant introuvable.');
        }

        $rules = [
            'nom' => 'required|max_length[100]',
            'prenom' => 'required|max_length[100]',
            'sexe' => 'required|in_list[M,F]',
            'date_naissance' => 'permit_empty|valid_date[Y-m-d]',
            'lieu_naissance' => 'permit_empty|max_length[150]',
            'situation' => 'permit_empty|in_list[Descolarise,Non_scolarise]',
            'derniere_classe' => 'permit_empty|max_length[100]',
            'cause_descolarisation' => 'permit_empty|max_length[65535]',
            'region' => 'permit_empty|max_length[100]',
            'departement' => 'permit_empty|max_length[100]',
            'commune' => 'permit_empty|max_length[100]',
            'nom_parent' => 'permit_empty|max_length[150]',
            'telephone_parent' => 'permit_empty|max_length[20]',
            'handicap' => 'required|in_list[oui,non]',
            'situation_familiale' => 'permit_empty|max_length[150]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to('/apprenant')
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $derniereClasse = $this->resolveClasseLabel((int) $this->request->getPost('classe_id')) ?: trim((string) $this->request->getPost('derniere_classe'));

        $this->apprenantModel->update($id, [
            'nom' => trim((string) $this->request->getPost('nom')),
            'prenom' => trim((string) $this->request->getPost('prenom')),
            'sexe' => $this->request->getPost('sexe'),
            'date_naissance' => $this->request->getPost('date_naissance') ?: null,
            'lieu_naissance' => trim((string) $this->request->getPost('lieu_naissance')),
            'situation' => $this->request->getPost('situation'),
            'derniere_classe' => $derniereClasse,
            'cause_descolarisation' => trim((string) $this->request->getPost('cause_descolarisation')),
            'region' => trim((string) $this->request->getPost('region')),
            'departement' => trim((string) $this->request->getPost('departement')),
            'commune' => trim((string) $this->request->getPost('commune')),
            'nom_parent' => trim((string) $this->request->getPost('nom_parent')),
            'telephone_parent' => trim((string) $this->request->getPost('telephone_parent')),
            'handicap' => $this->request->getPost('handicap'),
            'situation_familiale' => trim((string) $this->request->getPost('situation_familiale')),
        ]);

        return redirect()->to('/apprenant')
            ->with('success', 'Apprenant modifie avec succes.');
    }

    public function delete($id = null)
    {
        $id = (int) $id;
        $apprenant = $this->apprenantModel->find($id);

        if (! $apprenant) {
            return redirect()->to('/apprenant')
                ->with('error', 'Apprenant introuvable.');
        }

        $this->apprenantModel->delete($id);

        return redirect()->to('/apprenant')
            ->with('success', 'Apprenant supprime avec succes.');
    }

    protected function getClasseOptions(): array
    {
        $db = Database::connect();

        if (! $db->tableExists('classes')) {
            return [];
        }

        $rows = $db->table('classes')
            ->select('id, nom_classe')
            ->orderBy('nom_classe', 'ASC')
            ->get()
            ->getResultArray();

        return array_map(static function (array $row): array {
            return [
                'id' => $row['id'],
                'label' => $row['nom_classe'],
            ];
        }, $rows);
    }

    protected function resolveClasseLabel(int $classId): ?string
    {
        if ($classId <= 0) {
            return null;
        }

        $classe = $this->classeModel->find($classId);

        return $classe ? $classe['nom_classe'] : null;
    }

    protected function buildPerformanceData(array $apprenants, array $sessionNotes): array
    {
        return array_map(static function (array $apprenant) use ($sessionNotes): array {
            $id = (int) ($apprenant['id'] ?? 0);
            $test = (($id * 3) % 16) + 5;
            $evaluation = (($id * 7) % 16) + 5;
            $moyenne = round(($test + $evaluation) / 2, 2);

            $decision = 'Redouble';
            if ($moyenne >= 15) {
                $decision = 'Inséré';
            } elseif ($moyenne >= 10) {
                $decision = 'Passe';
            }

            return [
                'id' => $id,
                'eleve' => trim(sprintf('%s %s', $apprenant['prenom'] ?? '', $apprenant['nom'] ?? '')),
                'classe' => trim((string) ($apprenant['derniere_classe'] ?? '-')) ?: '-',
                'sexe' => trim((string) ($apprenant['sexe'] ?? '-')) ?: '-',
                'date_naissance' => trim((string) ($apprenant['date_naissance'] ?? '-')) ?: '-',
                'lieu_naissance' => trim((string) ($apprenant['lieu_naissance'] ?? '-')) ?: '-',
                'situation' => trim((string) ($apprenant['situation'] ?? '-')) ?: '-',
                'derniere_classe' => trim((string) ($apprenant['derniere_classe'] ?? '-')) ?: '-',
                'handicap' => trim((string) ($apprenant['handicap'] ?? '-')) ?: '-',
                'nom_parent' => trim((string) ($apprenant['nom_parent'] ?? '-')) ?: '-',
                'telephone_parent' => trim((string) ($apprenant['telephone_parent'] ?? '-')) ?: '-',
                'region' => trim((string) ($apprenant['region'] ?? '-')) ?: '-',
                'departement' => trim((string) ($apprenant['departement'] ?? '-')) ?: '-',
                'commune' => trim((string) ($apprenant['commune'] ?? '-')) ?: '-',
                'cause_descolarisation' => trim((string) ($apprenant['cause_descolarisation'] ?? '-')) ?: '-',
                'situation_familiale' => trim((string) ($apprenant['situation_familiale'] ?? '-')) ?: '-',
                'devoir' => sprintf('Devoir %d', $id),
                'test' => $test,
                'evaluation' => $evaluation,
                'composition' => round(($test * 0.4 + $evaluation * 0.6), 2),
                'moyenne' => $moyenne,
                'decision' => $decision,
                'notes' => $sessionNotes[$id] ?? [],
            ];
        }, $apprenants);
    }
}
