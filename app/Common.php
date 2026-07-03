<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

use App\Models\M_MenuModel;
use App\Models\M_SousMenuModel;
use CodeIgniter\Router\RouteCollection;

if (! function_exists('sidebarHasPermission')) {
    function sidebarHasPermission(array $permissions, int $menuId, int $sousMenuId = 0, int $permissionId = 1): bool
    {
        foreach ($permissions as $permission) {
            $dbSousMenu = ((int) ($permission['sous_menu_id'] ?? 0) === 0)
                ? (int) ($permission['menu_id'] ?? 0)
                : (int) ($permission['sous_menu_id'] ?? 0);

            if (
                (int) ($permission['menu_id'] ?? 0) === $menuId &&
                $dbSousMenu === $sousMenuId &&
                (int) ($permission['permission_id'] ?? 0) === $permissionId
            ) {
                return true;
            }
        }

        return false;
    }
}

if (! function_exists('sidebarIconClass')) {
    function sidebarIconClass(?string $icon, string $fallback = 'md md-folder'): string
    {
        $icon = trim((string) $icon);

        if ($icon === '') {
            return $fallback;
        }

        $map = [
            'fa fa-home' => 'md md-home',
            'fas fa-home' => 'md md-home',
            'fa fa-user-tie' => 'md md-account',
            'fas fa-user-tie' => 'md md-account',
            'fa fa-chalkboard-teacher' => 'md md-school',
            'fas fa-chalkboard-teacher' => 'md md-school',
            'fa fa-cogs' => 'md md-settings',
            'fas fa-cogs' => 'md md-settings',
            'fa fa-user-graduate' => 'md md-school',
            'fas fa-user-graduate' => 'md md-school',
            'fa fa-shield-alt' => 'md md-shield',
            'fas fa-shield-alt' => 'md md-shield',
            'fa fa-chalkboard' => 'md md-blackboard',
            'fas fa-chalkboard' => 'md md-blackboard',
            'fa-building' => 'md md-domain',
            'fas fa-building' => 'md md-domain',
            'fa fa-users' => 'md md-account-multiple',
            'fas fa-users' => 'md md-account-multiple',
            'fa fa-user-shield' => 'md md-account',
            'fas fa-user-shield' => 'md md-account',
            'fa fa-sitemap' => 'md md-view-list',
            'fas fa-sitemap' => 'md md-view-list',
            'fa fa-circle' => 'md md-circle',
            'fas fa-circle' => 'md md-circle',
        ];

        if (isset($map[$icon])) {
            return $map[$icon];
        }

        if (str_starts_with($icon, 'md ')) {
            return $icon;
        }

        if (str_starts_with($icon, 'fa ')) {
            return $icon;
        }

        return $fallback;
    }
}

if (! function_exists('getSidebarMenus')) {
    function getSidebarMenus(array $permissions = []): array
    {
        $session = session();
        $normalizedPermissions = array_map(static function (array $permission): string {
            $menuId = (int) ($permission['menu_id'] ?? 0);
            $subMenuId = (int) ($permission['sous_menu_id'] ?? 0);
            $permissionId = (int) ($permission['permission_id'] ?? 0);

            return $menuId . ':' . $subMenuId . ':' . $permissionId;
        }, $permissions);
        sort($normalizedPermissions);
        $permissionsSignature = sha1(implode('|', $normalizedPermissions));
        $cachedMenus = $session->get('sidebar_menus');
        $cachedSignature = $session->get('sidebar_menus_signature');

        if (is_array($cachedMenus) && $cachedSignature === $permissionsSignature) {
            return $cachedMenus;
        }

        static $menuCache = null;
        static $subMenuCache = null;
        static $globalMenuIds = ['6'];

        if ($menuCache === null) {
            $menuCache = (new M_MenuModel())
                ->orderBy('ordre', 'ASC')
                ->orderBy('id', 'ASC')
                ->findAll();

            $normalizeLabel = static function (?string $value): string {
                $value = trim((string) $value);
                $transliterated = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);

                if ($transliterated !== false) {
                    $value = $transliterated;
                }

                return strtolower($value);
            };

            usort($menuCache, static function (array $left, array $right) use ($normalizeLabel): int {
                $leftIsParametres = $normalizeLabel($left['nom_menu'] ?? '') === 'parametres';
                $rightIsParametres = $normalizeLabel($right['nom_menu'] ?? '') === 'parametres';

                if ($leftIsParametres !== $rightIsParametres) {
                    return $leftIsParametres ? 1 : -1;
                }

                $ordreCompare = ((int) ($left['ordre'] ?? 0)) <=> ((int) ($right['ordre'] ?? 0));

                if ($ordreCompare !== 0) {
                    return $ordreCompare;
                }

                return ((int) ($left['id'] ?? 0)) <=> ((int) ($right['id'] ?? 0));
            });
        }

        if ($subMenuCache === null) {
            $subMenuCache = (new M_SousMenuModel())
                ->orderBy('ordre', 'ASC')
                ->orderBy('id', 'ASC')
                ->findAll();
        }

        $subMenusByParent = [];

        foreach ($subMenuCache as $subMenu) {
            $subMenusByParent[(int) ($subMenu['menu_id'] ?? 0)][] = $subMenu;
        }

        $navigation = [];

        foreach ($menuCache as $menu) {
            if ((int) ($menu['statut'] ?? 1) !== 1) {
                continue;
            }

            $menuId = (int) ($menu['id'] ?? 0);
            $menuPermissionId = (int) ($menu['permission_id'] ?? 1);
            $menuHasPermission = sidebarHasPermission($permissions, $menuId, $menuId, $menuPermissionId);
            $children = [];
            $normalizeLabel = static function (?string $value): string {
                $value = trim((string) $value);
                $transliterated = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);

                if ($transliterated !== false) {
                    $value = $transliterated;
                }

                return strtolower($value);
            };
            $requiresOwnPermission = $menuId === 6 || $normalizeLabel($menu['nom_menu'] ?? '') === 'parametres';

            foreach ($subMenusByParent[$menuId] ?? [] as $subMenu) {
                if ((int) ($subMenu['statut'] ?? 1) !== 1) {
                    continue;
                }

                $subMenuId = (int) ($subMenu['id'] ?? 0);
                $subMenuPermissionId = (int) ($subMenu['permission_id'] ?? 1);
                $normalizedSubMenuId = in_array((string) $menuId, $globalMenuIds, true)
                    ? $menuId
                    : $subMenuId;

                if (
                    ! sidebarHasPermission($permissions, $menuId, $subMenuId, $subMenuPermissionId)
                    && ! sidebarHasPermission($permissions, $menuId, $normalizedSubMenuId, $subMenuPermissionId)
                ) {
                    continue;
                }

                $children[] = $subMenu;
            }

            $isLeafMenu = empty($children);
            $hasVisibleNode = $menuHasPermission || (! $requiresOwnPermission && ! $isLeafMenu);

            if (! $hasVisibleNode) {
                continue;
            }

            if ($isLeafMenu && trim((string) ($menu['url'] ?? '')) === '') {
                continue;
            }

            $menu['children'] = $children;
            $menu['is_leaf'] = $isLeafMenu;
            $navigation[] = $menu;
        }

        $session->set('sidebar_menus', $navigation);
        $session->set('sidebar_menus_signature', $permissionsSignature);

        return $navigation;
    }
}

if (! function_exists('registerDynamicMenuRoutes')) {
    function registerDynamicMenuRoutes(RouteCollection $routes, array $reservedPaths = []): void
    {
        static $registered = false;

        if ($registered) {
            return;
        }

        $registered = true;

        $normalizePath = static function (?string $path): string {
            return trim(trim((string) $path), '/');
        };

        $reservedLookup = [];
        foreach ($reservedPaths as $path) {
            $reservedLookup[$normalizePath($path)] = true;
        }

        $seenPaths = [];

        try {
            $menus = (new M_MenuModel())
                ->where('statut', 1)
                ->orderBy('ordre', 'ASC')
                ->orderBy('id', 'ASC')
                ->findAll();

            $subMenus = (new M_SousMenuModel())
                ->where('statut', 1)
                ->orderBy('menu_id', 'ASC')
                ->orderBy('ordre', 'ASC')
                ->orderBy('id', 'ASC')
                ->findAll();
        } catch (\Throwable $e) {
            return;
        }

        foreach ($menus as $menu) {
            $path = $normalizePath($menu['url'] ?? '');

            if ($path === '' || isset($reservedLookup[$path]) || isset($seenPaths[$path])) {
                continue;
            }

            $menuId = (int) ($menu['id'] ?? 0);
            $permissionId = (int) ($menu['permission_id'] ?? 1);

            $routes->get($path, 'C_DynamicPageController::show/menu/' . $menuId, [
                'filter' => 'permission:' . $menuId . ',' . $menuId . ',' . $permissionId,
            ]);

            $seenPaths[$path] = true;
        }

        foreach ($subMenus as $subMenu) {
            $path = $normalizePath($subMenu['url'] ?? '');

            if ($path === '' || isset($reservedLookup[$path]) || isset($seenPaths[$path])) {
                continue;
            }

            $menuId = (int) ($subMenu['menu_id'] ?? 0);
            $subMenuId = (int) ($subMenu['id'] ?? 0);
            $permissionId = (int) ($subMenu['permission_id'] ?? 1);

            $routes->get($path, 'C_DynamicPageController::show/submenu/' . $subMenuId, [
                'filter' => 'permission:' . $menuId . ',' . $subMenuId . ',' . $permissionId,
            ]);

            $seenPaths[$path] = true;
        }
    }
}
