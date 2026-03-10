<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Database;

class PermissionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if(!session()->get('logged_in')){
            return redirect()->to('/login');
        }

        $role_id = session()->get('role_id');

        $url = service('uri')->getSegment(1);

        $db = Database::connect();

        $menu = $db->table('menus')
                   ->where('url', $url)
                   ->get()
                   ->getRowArray();

        if(!$menu){
            return;
        }

        $permission = $db->table('role_permissions')
                         ->where('role_id', $role_id)
                         ->where('menu_id', $menu['id'])
                         ->get()
                         ->getRowArray();

        if(!$permission){
            return redirect()->to('/dashboard')
                   ->with('error','Accès refusé');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}