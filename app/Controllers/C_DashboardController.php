<?php namespace App\Controllers;

class C_DashboardController extends BaseController
{
    public function index()
    {
        // Vérifier si l'utilisateur est connecté
        if(!session()->get('logged_in')){
            return redirect()->to('/login');
        }

        return view('V_dashboard');
    }
}