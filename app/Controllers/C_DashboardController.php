<?php

namespace App\Controllers;

use App\Models\M_StructureModel;

class C_DashboardController extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $structureModel = new M_StructureModel();

        $data['user_permissions'] = $this->getUserPermissions();
        $data['totalStructures'] = $structureModel->countAll();
        $data['structuresOuvertes'] = $structureModel->where('etat', 'OUVERT')->countAllResults();
        $data['structuresFermees'] = $structureModel->where('etat', 'FERME')->countAllResults();
        $data['structuresValidees'] = $structureModel->where('etat', 'VALIDE')->countAllResults();

        return view('V_dashboard', $data);
    }
}
