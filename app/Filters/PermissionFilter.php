<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class PermissionFilter implements FilterInterface
{
    protected function getDeniedMessage($permissionId): string
    {
        return match ((int) $permissionId) {
            2 => "Acces refuse : vous n'avez pas le droit d'ajouter.",
            3 => "Acces refuse : vous n'avez pas le droit de modifier.",
            4 => "Acces refuse : vous n'avez pas le droit de supprimer.",
            default => "Acces refuse : vous n'avez pas le droit d'acceder a cette ressource.",
        };
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $role_id = session()->get('role_id');
        if (!$role_id) {
            return redirect()->to('/login');
        }

        if (empty($arguments) || count($arguments) < 3) {
            return;
        }

        [$menuId, $sousMenuId, $permissionId] = $arguments;

        $permissions = session()->get('user_permissions') ?? [];
        $permission = null;

        foreach ($permissions as $currentPermission) {
            $dbSousMenuId = ((int) ($currentPermission['sous_menu_id'] ?? 0) === 0)
                ? (int) ($currentPermission['menu_id'] ?? 0)
                : (int) ($currentPermission['sous_menu_id'] ?? 0);

            if (
                (int) ($currentPermission['menu_id'] ?? 0) === (int) $menuId &&
                $dbSousMenuId === (int) $sousMenuId &&
                (int) ($currentPermission['permission_id'] ?? 0) === (int) $permissionId
            ) {
                $permission = $currentPermission;
                break;
            }
        }

        if (!$permission) {
            return redirect()->back()->with('access_denied', $this->getDeniedMessage($permissionId));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
