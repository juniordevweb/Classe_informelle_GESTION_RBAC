<?php

namespace App\Controllers;

use App\Models\M_StructureModel;
use App\Models\M_OperateurModel;
use App\Requests\StoreStructureRequest;
use App\Requests\UpdateStructureRequest;
use App\Policies\StructurePolicy;
use App\Services\StructureCodeService;
use CodeIgniter\HTTP\ResponseInterface;

class C_StructureController extends BaseController
{
    protected $structureModel;
    protected $operateurModel;
    protected $storeRequest;
    protected $updateRequest;
    protected $policy;
    protected $codeService;

    public function __construct()
    {
        $this->structureModel = new M_StructureModel();
        $this->operateurModel = new M_OperateurModel();
        $this->storeRequest = new StoreStructureRequest();
        $this->updateRequest = new UpdateStructureRequest();
        $this->policy = new StructurePolicy();
        $this->codeService = new StructureCodeService();
    }

    /**
     * Affiche la liste des structures.
     */
    public function index()
    {
        $user = session()->get();

        if (!$this->policy->view($user)) {
            return redirect()->to('/dashboard')->with('error', 'Accès non autorisé.');
        }

        $perPage = 3;
        $page = $this->request->getGet('page') ?? 1;
        $search = $this->request->getGet('search');
        $region = $this->request->getGet('region');
        $etat = $this->request->getGet('etat');
        $operateur = $this->request->getGet('operateur');

        $query = $this->structureModel->select('structures.*, operateur.nom_organisation as nom_operateur, COUNT(classes.id) as nombre_classes')
                                      ->join('operateur', 'operateur.id = structures.operateur_id')
                                      ->join('classes', 'classes.structure_id = structures.id', 'left')
                                      ->groupBy('structures.id');

        if ($search) {
            $query->like('nom_structure', $search);
        }

        if ($region) {
            $query->where('region', $region);
        }

        if ($etat) {
            $query->where('etat', $etat);
        }

        if ($operateur) {
            $query->where('operateur_id', $operateur);
        }

        $structures = $query->paginate($perPage, 'structures', $page);
        $pager = $this->structureModel->pager;

        $operateurs = $this->operateurModel->findAll();
        $regions = $this->structureModel->distinct()->select('region')->findAll();

        // Compteurs
        $totalStructures = $this->structureModel->countAll();
        $structuresOuvertes = $this->structureModel->where('etat', 'OUVERT')->countAllResults();
        $structuresFermees = $this->structureModel->where('etat', 'FERME')->countAllResults();
        $structuresValidees = $this->structureModel->where('etat', 'VALIDE')->countAllResults();

        return view('V_structures_index', [
            'user_permissions' => $this->getUserPermissions(),
            'structures' => $structures,
            'pager' => $pager,
            'operateurs' => $operateurs,
            'regions' => array_column($regions, 'region'),
            'totalStructures' => $totalStructures,
            'structuresOuvertes' => $structuresOuvertes,
            'structuresFermees' => $structuresFermees,
            'structuresValidees' => $structuresValidees,
        ]);
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        $user = session()->get();

        if (!$this->policy->create($user)) {
            return redirect()->to('/structures')->with('error', 'Accès non autorisé.');
        }

        $operateurs = $this->operateurModel->findAll();

        return view('V_structures_create', [
            'operateurs' => $operateurs,
        ]);
    }

    /**
     * Enregistre une nouvelle structure.
     */
    public function store()
    {
        $user = session()->get();

        if (!$this->policy->create($user)) {
            return redirect()->to('/structures')->with('error', 'Accès non autorisé.');
        }

        $data = $this->request->getPost();

        if (!$this->storeRequest->validate($data)) {
            return redirect()->back()->withInput()->with('errors', $this->storeRequest->getErrors());
        }

        $data['code_structure'] = $this->codeService->generateCode();

        if ($this->structureModel->insert($data)) {
            return redirect()->to('/structures')->with('success', 'Structure créée avec succès.');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de la création de la structure.');
    }

    /**
     * Affiche les détails d'une structure.
     */
    public function show($id)
    {
        $user = session()->get();

        $structure = $this->structureModel->find($id);

        if (!$structure || !$this->policy->view($user)) {
            return redirect()->to('/structures')->with('error', 'Structure non trouvée ou accès non autorisé.');
        }

        $operateur = $this->operateurModel->find($structure['operateur_id']);
        
        // Compter les classes liées à cette structure
        $db = \Config\Database::connect();
        $nombreClasses = $db->table('classes')->where('structure_id', $id)->countAllResults();
        
        // Pour les apprenants, on initialise à 0 puisque la relation n'existe pas actuellement
        $nombreApprenants = 0;

        return view('V_structures_show', [
            'structure' => $structure,
            'operateur' => $operateur,
            'nombreClasses' => $nombreClasses,
            'nombreApprenants' => $nombreApprenants,
        ]);
    }

    /**
     * Affiche le formulaire d'édition.
     */
    public function edit($id)
    {
        $user = session()->get();

        $structure = $this->structureModel->find($id);

        if (!$structure || !$this->policy->update($user, $this->structureModel)) {
            return redirect()->to('/structures')->with('error', 'Structure non trouvée ou accès non autorisé.');
        }

        $operateurs = $this->operateurModel->findAll();

        return view('V_structures_edit', [
            'structure' => $structure,
            'operateurs' => $operateurs,
        ]);
    }

    /**
     * Met à jour une structure.
     */
    public function update($id)
    {
        $user = session()->get();

        $structure = $this->structureModel->find($id);

        if (!$structure || !$this->policy->update($user, $this->structureModel)) {
            return redirect()->to('/structures')->with('error', 'Structure non trouvée ou accès non autorisé.');
        }

        $data = $this->request->getPost();

        if (!$this->updateRequest->validate($data)) {
            return redirect()->back()->withInput()->with('errors', $this->updateRequest->getErrors());
        }

        if ($this->structureModel->update($id, $data)) {
            return redirect()->to('/structures')->with('success', 'Structure mise à jour avec succès.');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de la mise à jour de la structure.');
    }

    /**
     * Supprime une structure (soft delete).
     */
    public function destroy($id)
    {
        $user = session()->get();

        $structure = $this->structureModel->find($id);

        if (!$structure || !$this->policy->delete($user, $this->structureModel)) {
            return redirect()->to('/structures')->with('error', 'Structure non trouvée ou accès non autorisé.');
        }

        if ($this->structureModel->delete($id)) {
            return redirect()->to('/structures')->with('success', 'Structure supprimée avec succès.');
        }

        return redirect()->back()->with('error', 'Erreur lors de la suppression de la structure.');
    }

    /**
     * API: Retourne les données d'une structure en JSON.
     */
    public function apiGet($id)
    {
        $user = session()->get();

        $structure = $this->structureModel->find($id);

        if (!$structure || !$this->policy->view($user)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Structure non trouvée ou accès non autorisé.'
            ]);
        }

        $operateur = $this->operateurModel->find($structure['operateur_id']);

        // Compter les classes liées à cette structure
        $db = \Config\Database::connect();
        $nombreClasses = $db->table('classes')->where('structure_id', $id)->countAllResults();

        $data = array_merge($structure, [
            'operateur_nom' => $operateur['nom_organisation'] ?? 'N/A',
            'nombre_classes' => $nombreClasses,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'data' => $data
        ]);
    }
}
