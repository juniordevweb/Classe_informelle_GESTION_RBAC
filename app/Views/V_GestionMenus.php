<?= $this->include('templates/header') ?>
<?= $this->include('templates/top_bar') ?>
<?= $this->include('templates/left_sidebar') ?>

<?php
$user_permissions = $user_permissions ?? [];
$menus = $menus ?? [];
$sous_menus = $sous_menus ?? [];
$permissions = $permissions ?? [];

$hasMenuActionPermission = static function (array $permissionsList, int $menuId, int $sousMenuId, int $permissionId): bool {
    $globalMenuIds = [6];
    $normalizedSousMenuId = in_array($menuId, $globalMenuIds, true) ? $menuId : $sousMenuId;

    foreach ($permissionsList as $permission) {
        $dbSousMenu = ((int) ($permission['sous_menu_id'] ?? 0) === 0)
            ? (int) ($permission['menu_id'] ?? 0)
            : (int) ($permission['sous_menu_id'] ?? 0);

        if (
            (int) ($permission['menu_id'] ?? 0) === $menuId &&
            ($dbSousMenu === $sousMenuId || $dbSousMenu === $normalizedSousMenuId) &&
            (int) ($permission['permission_id'] ?? 0) === $permissionId
        ) {
            return true;
        }
    }

    return false;
};

$canAddMenu = $hasMenuActionPermission($user_permissions, 6, 17, 2);
$canEditMenu = $hasMenuActionPermission($user_permissions, 6, 17, 3);
$canDeleteMenu = $hasMenuActionPermission($user_permissions, 6, 17, 4);

$permissionLabels = [];
foreach ($permissions as $permission) {
    $permissionLabels[(int) $permission['id']] = $permission['nom_permission'] ?? '';
}

$menusById = [];
foreach ($menus as $menu) {
    $menusById[(int) $menu['id']] = $menu;
}

$menuSubCounts = [];
foreach ($sous_menus as $subMenu) {
    if (trim((string) ($subMenu['url'] ?? ''), '/') === 'structures/create') {
        continue;
    }

    $menuSubCounts[(int) ($subMenu['menu_id'] ?? 0)] = ($menuSubCounts[(int) ($subMenu['menu_id'] ?? 0)] ?? 0) + 1;
}

$statusBadge = static function (int $status): array {
    return $status === 1
        ? ['label' => 'Actif', 'class' => 'bg-success']
        : ['label' => 'Inactif', 'class' => 'bg-secondary'];
};

$flashSuccess = session()->getFlashdata('success');
$flashSuccessDelete = session()->getFlashdata('success_delete');
$flashError = session()->getFlashdata('error');
?>

<style>
    .menu-page .card-header {
        background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    }

    .menu-icon-preview {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: rgba(13, 110, 253, 0.08);
        color: #0d6efd;
        font-size: 14px;
    }

    .table td,
    .table th {
        vertical-align: middle;
    }

    .truncate-url {
        max-width: 220px;
        display: inline-block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        vertical-align: bottom;
    }
</style>

<br><br><br>

<div class="content-page menu-page">
    <div class="container-fluid mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-header text-white d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">
                        <i class="fa fa-sitemap me-2  "></i> <b class="text-white">Gestion des menus</b>
                    </h4>
                    <small class="text-white-50">Administration des menus principaux et des sous-menus</small>
                </div>

                <?php if ($canAddMenu): ?>
                    <div class="d-flex gap-2">
                        <button type="button" id="openAddMenuModal" class="btn btn-light btn-sm">
                            <i class="fa fa-folder-plus me-1"></i> Ajouter un menu
                        </button>
                        <button type="button" id="openAddSubMenuModal" class="btn btn-outline-light btn-sm">
                            <i class="fa fa-list me-1"></i> Ajouter un sous-menu
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <div class="card-body">
                <?php if ($flashSuccess): ?>
                    <div class="alert alert-success"><?= esc($flashSuccess) ?></div>
                <?php endif; ?>

                <?php if ($flashSuccessDelete): ?>
                    <div class="alert alert-success"><?= esc($flashSuccessDelete) ?></div>
                <?php endif; ?>

                <?php if ($flashError): ?>
                    <div class="alert alert-danger"><?= esc($flashError) ?></div>
                <?php endif; ?>

                <ul class="nav nav-tabs mb-4" id="menusTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" id="menus-tab" data-bs-toggle="tab" data-bs-target="#menus-pane" role="tab">
                            Menus principaux <span class="badge bg-primary ms-1"><?= count($menus) ?></span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" id="submenus-tab" data-bs-toggle="tab" data-bs-target="#submenus-pane" role="tab">
                            Sous-menus <span class="badge bg-primary ms-1"><?= count($sous_menus) ?></span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="menus-pane" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Menu</th>
                                        <th>Icône</th>
                                        <th>URL</th>
                                        <th>Permission</th>
                                        <th>Ordre</th>
                                        <th>Statut</th>
                                        <th>Sous-menus</th>
                                        <?php if ($canEditMenu || $canDeleteMenu): ?>
                                            <th class="text-center" width="160">Actions</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($menus as $menu): ?>
                                        <?php
                                        $menuStatus = $statusBadge((int) ($menu['statut'] ?? 1));
                                        $permissionId = (int) ($menu['permission_id'] ?? 1);
                                        ?>
                                        <tr>
                                            <td class="fw-semibold"><?= esc($menu['nom_menu'] ?? '') ?></td>
                                            <td>
                                                <span class="menu-icon-preview">
                                                    <i class="<?= esc(sidebarIconClass($menu['icone'] ?? null)) ?>"></i>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if (! empty($menu['url'])): ?>
                                                    <span class="truncate-url"><?= esc($menu['url']) ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= esc($permissionLabels[$permissionId] ?? ('#' . $permissionId)) ?></td>
                                            <td><?= esc((string) ($menu['ordre'] ?? 0)) ?></td>
                                            <td><span class="badge <?= esc($menuStatus['class']) ?>"><?= esc($menuStatus['label']) ?></span></td>
                                            <td>
                                                <span class="badge bg-info">
                                                    <?= esc((string) ($menuSubCounts[(int) $menu['id']] ?? 0)) ?>
                                                </span>
                                            </td>
                                            <?php if ($canEditMenu || $canDeleteMenu): ?>
                                                <td class="text-center">
                                                    <?php if ($canEditMenu): ?>
                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-outline-primary editMenuBtn"
                                                            data-id="<?= esc($menu['id'], 'attr') ?>"
                                                            data-nom_menu="<?= esc($menu['nom_menu'] ?? '', 'attr') ?>"
                                                            data-icone="<?= esc($menu['icone'] ?? '', 'attr') ?>"
                                                            data-url="<?= esc($menu['url'] ?? '', 'attr') ?>"
                                                            data-ordre="<?= esc((string) ($menu['ordre'] ?? 0), 'attr') ?>"
                                                            data-permission_id="<?= esc((string) ($menu['permission_id'] ?? 1), 'attr') ?>"
                                                            data-statut="<?= esc((string) ($menu['statut'] ?? 1), 'attr') ?>">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                    <?php endif; ?>

                                                    <?php if ($canDeleteMenu): ?>
                                                        <a href="<?= base_url('menus/delete-menu/' . $menu['id']) ?>"
                                                           class="btn btn-sm btn-outline-danger"
                                                           onclick="return confirm('Supprimer ce menu et tous ses sous-menus ?');">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="submenus-pane" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Parent</th>
                                        <th>Sous-menu</th>
                                        <th>Icône</th>
                                        <th>URL</th>
                                        <th>Permission</th>
                                        <th>Ordre</th>
                                        <th>Statut</th>
                                        <?php if ($canEditMenu || $canDeleteMenu): ?>
                                            <th class="text-center" width="160">Actions</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($sous_menus as $subMenu): ?>
                                        <?php if (trim((string) ($subMenu['url'] ?? ''), '/') === 'structures/create') { continue; } ?>
                                        <?php
                                        $subStatus = $statusBadge((int) ($subMenu['statut'] ?? 1));
                                        $subPermissionId = (int) ($subMenu['permission_id'] ?? 1);
                                        ?>
                                        <tr>
                                            <td><?= esc($subMenu['parent_menu'] ?? '') ?></td>
                                            <td class="fw-semibold"><?= esc($subMenu['nom_sous_menu'] ?? '') ?></td>
                                            <td>
                                                <span class="menu-icon-preview">
                                                    <i class="<?= esc(sidebarIconClass($subMenu['icon'] ?? null, 'md md-circle')) ?>"></i>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="truncate-url"><?= esc($subMenu['url'] ?? '') ?></span>
                                            </td>
                                            <td><?= esc($permissionLabels[$subPermissionId] ?? ('#' . $subPermissionId)) ?></td>
                                            <td><?= esc((string) ($subMenu['ordre'] ?? 0)) ?></td>
                                            <td><span class="badge <?= esc($subStatus['class']) ?>"><?= esc($subStatus['label']) ?></span></td>
                                            <?php if ($canEditMenu || $canDeleteMenu): ?>
                                                <td class="text-center">
                                                    <?php if ($canEditMenu): ?>
                                                        <button
                                                            type="button"
                                                            class="btn btn-sm btn-outline-primary editSubMenuBtn"
                                                            data-id="<?= esc($subMenu['id'], 'attr') ?>"
                                                            data-menu_id="<?= esc($subMenu['menu_id'], 'attr') ?>"
                                                            data-nom_sous_menu="<?= esc($subMenu['nom_sous_menu'] ?? '', 'attr') ?>"
                                                            data-icon="<?= esc($subMenu['icon'] ?? '', 'attr') ?>"
                                                            data-url="<?= esc($subMenu['url'] ?? '', 'attr') ?>"
                                                            data-ordre="<?= esc((string) ($subMenu['ordre'] ?? 0), 'attr') ?>"
                                                            data-permission_id="<?= esc((string) ($subMenu['permission_id'] ?? 1), 'attr') ?>"
                                                            data-statut="<?= esc((string) ($subMenu['statut'] ?? 1), 'attr') ?>">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                    <?php endif; ?>

                                                    <?php if ($canDeleteMenu): ?>
                                                        <a href="<?= base_url('menus/delete-submenu/' . $subMenu['id']) ?>"
                                                           class="btn btn-sm btn-outline-danger"
                                                           onclick="return confirm('Supprimer ce sous-menu ?');">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($canAddMenu): ?>
<div class="modal fade" id="addMenuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="<?= base_url('menus/save-menu') ?>">
                <?= csrf_field() ?>
                <div class="modal-header text-white" style="background: linear-gradient(135deg,#0d6efd,#0a58ca);">
                    <h5 class="modal-title ">Ajouter un menu</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom du menu</label>
                            <input type="text" name="nom_menu" class="form-control" value="<?= esc(old('nom_menu')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Icône</label>
                            <input type="text" name="icone" class="form-control" value="<?= esc(old('icone')) ?>" placeholder="fa fa-folder">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Route / URL</label>
                            <input type="text" name="url" class="form-control" value="<?= esc(old('url')) ?>" placeholder="/menus">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ordre</label>
                            <input type="number" name="ordre" class="form-control" value="<?= esc(old('ordre') ?? '0') ?>" min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Permission</label>
                            <select name="permission_id" class="form-select" required>
                                <option value="1" <?= old('permission_id', '1') == '1' ? 'selected' : '' ?>>read</option>
                                <?php foreach ($permissions as $permission): ?>
                                    <?php if ((int) $permission['id'] === 1) { continue; } ?>
                                    <option value="<?= esc($permission['id']) ?>" <?= old('permission_id') == $permission['id'] ? 'selected' : '' ?>>
                                        <?= esc($permission['nom_permission']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Statut</label>
                            <select name="statut" class="form-select" required>
                                <option value="1" <?= old('statut', '1') == '1' ? 'selected' : '' ?>>Actif</option>
                                <option value="0" <?= old('statut') === '0' ? 'selected' : '' ?>>Inactif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editMenuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="<?= base_url('menus/update-menu') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="editMenuId">
                <div class="modal-header text-white" style="background: linear-gradient(135deg,#0d6efd,#0a58ca);">
                    <h5 class="modal-title">Modifier le menu</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom du menu</label>
                            <input type="text" name="nom_menu" id="editMenuNom" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Icône</label>
                            <input type="text" name="icone" id="editMenuIcone" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Route / URL</label>
                            <input type="text" name="url" id="editMenuUrl" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ordre</label>
                            <input type="number" name="ordre" id="editMenuOrdre" class="form-control" min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Permission</label>
                            <select name="permission_id" id="editMenuPermission" class="form-select" required>
                                <?php foreach ($permissions as $permission): ?>
                                    <option value="<?= esc($permission['id']) ?>">
                                        <?= esc($permission['nom_permission']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Statut</label>
                            <select name="statut" id="editMenuStatus" class="form-select" required>
                                <option value="1">Actif</option>
                                <option value="0">Inactif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addSubMenuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="<?= base_url('menus/save-submenu') ?>">
                <?= csrf_field() ?>
                <div class="modal-header text-white" style="background: linear-gradient(135deg,#0d6efd,#0a58ca);">
                    <h5 class="modal-title">Ajouter un sous-menu</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Parent</label>
                            <select name="menu_id" class="form-select" required>
                                <option value="">Choisir un menu parent</option>
                                <?php foreach ($menus as $menu): ?>
                                    <option value="<?= esc($menu['id']) ?>" <?= old('menu_id') == $menu['id'] ? 'selected' : '' ?>>
                                        <?= esc($menu['nom_menu']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nom du sous-menu</label>
                            <input type="text" name="nom_sous_menu" class="form-control" value="<?= esc(old('nom_sous_menu')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Icône</label>
                            <input type="text" name="icon" class="form-control" value="<?= esc(old('icon')) ?>" placeholder="fa fa-link">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Route / URL</label>
                            <input type="text" name="url" class="form-control" value="<?= esc(old('url')) ?>" placeholder="/menus">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ordre</label>
                            <input type="number" name="ordre" class="form-control" value="<?= esc(old('ordre') ?? '0') ?>" min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Permission</label>
                            <select name="permission_id" class="form-select" required>
                                <option value="1" <?= old('permission_id', '1') == '1' ? 'selected' : '' ?>>read</option>
                                <?php foreach ($permissions as $permission): ?>
                                    <?php if ((int) $permission['id'] === 1) { continue; } ?>
                                    <option value="<?= esc($permission['id']) ?>" <?= old('permission_id') == $permission['id'] ? 'selected' : '' ?>>
                                        <?= esc($permission['nom_permission']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Statut</label>
                            <select name="statut" class="form-select" required>
                                <option value="1" <?= old('statut', '1') == '1' ? 'selected' : '' ?>>Actif</option>
                                <option value="0" <?= old('statut') === '0' ? 'selected' : '' ?>>Inactif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editSubMenuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="<?= base_url('menus/update-submenu') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="editSubMenuId">
                <div class="modal-header text-white" style="background: linear-gradient(135deg,#0d6efd,#0a58ca);">
                    <h5 class="modal-title">Modifier le sous-menu</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Parent</label>
                            <select name="menu_id" id="editSubMenuParent" class="form-select" required>
                                <?php foreach ($menus as $menu): ?>
                                    <option value="<?= esc($menu['id']) ?>">
                                        <?= esc($menu['nom_menu']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nom du sous-menu</label>
                            <input type="text" name="nom_sous_menu" id="editSubMenuName" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Icône</label>
                            <input type="text" name="icon" id="editSubMenuIcon" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Route / URL</label>
                            <input type="text" name="url" id="editSubMenuUrl" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Ordre</label>
                            <input type="number" name="ordre" id="editSubMenuOrdre" class="form-control" min="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Permission</label>
                            <select name="permission_id" id="editSubMenuPermission" class="form-select" required>
                                <?php foreach ($permissions as $permission): ?>
                                    <option value="<?= esc($permission['id']) ?>">
                                        <?= esc($permission['nom_permission']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Statut</label>
                            <select name="statut" id="editSubMenuStatus" class="form-select" required>
                                <option value="1">Actif</option>
                                <option value="0">Inactif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const addMenuModal = document.getElementById('addMenuModal');
    const addSubMenuModal = document.getElementById('addSubMenuModal');
    const openAddMenuModal = document.getElementById('openAddMenuModal');
    const openAddSubMenuModal = document.getElementById('openAddSubMenuModal');

    if (openAddMenuModal && addMenuModal) {
        openAddMenuModal.addEventListener('click', () => {
            const modal = new bootstrap.Modal(addMenuModal);
            modal.show();
        });
    }

    if (openAddSubMenuModal && addSubMenuModal) {
        openAddSubMenuModal.addEventListener('click', () => {
            const modal = new bootstrap.Modal(addSubMenuModal);
            modal.show();
        });
    }

    const editMenuModal = document.getElementById('editMenuModal');
    if (editMenuModal) {
        document.querySelectorAll('.editMenuBtn').forEach((button) => {
            button.addEventListener('click', function () {
                document.getElementById('editMenuId').value = this.dataset.id || '';
                document.getElementById('editMenuNom').value = this.dataset.nom_menu || '';
                document.getElementById('editMenuIcone').value = this.dataset.icone || '';
                document.getElementById('editMenuUrl').value = this.dataset.url || '';
                document.getElementById('editMenuOrdre').value = this.dataset.ordre || 0;
                document.getElementById('editMenuPermission').value = this.dataset.permission_id || '1';
                document.getElementById('editMenuStatus').value = this.dataset.statut || '1';

                const modal = new bootstrap.Modal(editMenuModal);
                modal.show();
            });
        });
    }

    const editSubMenuModal = document.getElementById('editSubMenuModal');
    if (editSubMenuModal) {
        document.querySelectorAll('.editSubMenuBtn').forEach((button) => {
            button.addEventListener('click', function () {
                document.getElementById('editSubMenuId').value = this.dataset.id || '';
                document.getElementById('editSubMenuParent').value = this.dataset.menu_id || '';
                document.getElementById('editSubMenuName').value = this.dataset.nom_sous_menu || '';
                document.getElementById('editSubMenuIcon').value = this.dataset.icon || '';
                document.getElementById('editSubMenuUrl').value = this.dataset.url || '';
                document.getElementById('editSubMenuOrdre').value = this.dataset.ordre || 0;
                document.getElementById('editSubMenuPermission').value = this.dataset.permission_id || '1';
                document.getElementById('editSubMenuStatus').value = this.dataset.statut || '1';

                const modal = new bootstrap.Modal(editSubMenuModal);
                modal.show();
            });
        });
    }
});
</script>

<?= $this->include('templates/footer') ?>
