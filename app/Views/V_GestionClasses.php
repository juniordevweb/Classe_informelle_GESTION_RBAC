<?= $this->include('templates/header') ?>
<?= $this->include('templates/top_bar') ?>
<?= $this->include('templates/left_sidebar') ?>

<?php
$user_permissions = $user_permissions ?? [];
$classes = $classes ?? [];
$structures = $structures ?? [];
$facilitateurs = $facilitateurs ?? [];

$hasClassePermission = static function (array $permissions, int $menuId, int $sousMenuId, int $permissionId): bool {
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
};

function getLabel(array $items, $id, string $default = ''): string
{
    foreach ($items as $item) {
        if ((int) ($item['id'] ?? 0) === (int) $id) {
            return $item['label'] ?? $default;
        }
    }

    return $default;
}

$canAddClasse = $hasClassePermission($user_permissions, 7, 14, 2);
$canEditClasse = $hasClassePermission($user_permissions, 7, 14, 3);
$canDeleteClasse = $hasClassePermission($user_permissions, 7, 14, 4);
$showActionsColumn = $canEditClasse || $canDeleteClasse;
?>

<div class="content-page">
    <div class="container mt-4">
        <br><br><br>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3>Gestion des classes</h3>
                <p class="text-muted mb-0">verifiez bien les donnees a leur saisie.</p>
            </div>
            <?php if ($canAddClasse): ?>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClasseModal">
                    <i class="fa fa-plus me-1"></i> Ajouter classe
                </button>
            <?php endif; ?>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nom classe</th>
                                <th>Code</th>
                                <th>Structure</th>
                                <th>Niveau</th>
                                <th>Langue</th>
                                <th>Date ouverture</th>
                                <th>Facilitateur</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($classes)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Aucune classe enregistre.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($classes as $classe): ?>
                                    <tr>
                                        <td><?= esc($classe['nom_classe']) ?></td>
                                        <td><?= esc($classe['code_classe']) ?></td>
                                        <td><?= esc(getLabel($structures, $classe['structure_id'], '-')) ?></td>
                                        <td><?= esc($classe['niveau']) ?></td>
                                        <td><?= esc($classe['langue'] ?? '-') ?></td>
                                        <td><?= esc($classe['date_ouverture'] ? date('d/m/Y', strtotime($classe['date_ouverture'])) : '-') ?></td>
                                        <td><?= esc(getLabel($facilitateurs, $classe['facilitateur_id'], '-')) ?></td>
                                        <td class="text-nowrap">
                                            <?php if ($canEditClasse): ?>
                                                <button class="btn btn-sm btn-outline-secondary me-1 editClasseBtn"
                                                    data-id="<?= esc($classe['id'], 'attr') ?>"
                                                    data-nom_classe="<?= esc($classe['nom_classe'], 'attr') ?>"
                                                    data-code_classe="<?= esc($classe['code_classe'], 'attr') ?>"
                                                    data-structure_id="<?= esc($classe['structure_id'], 'attr') ?>"
                                                    data-niveau="<?= esc($classe['niveau'], 'attr') ?>"
                                                    data-langue="<?= esc($classe['langue'] ?? '', 'attr') ?>"
                                                    data-date_ouverture="<?= esc($classe['date_ouverture'] ?? '', 'attr') ?>"
                                                    data-facilitateur_id="<?= esc($classe['facilitateur_id'], 'attr') ?>"
                                                    title="Modifier">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            <?php endif; ?>

                                            <a href="<?= base_url('apprenant') ?>?classe_id=<?= esc($classe['id'], 'attr') ?>&open_modal=1" class="btn btn-sm btn-outline-info me-1" title="Ajouter élèves">
                                                <i class="fa fa-user-plus"></i>
                                            </a>

                                            <?php if ($canDeleteClasse): ?>
                                                <button class="btn btn-sm btn-outline-danger deleteClasseBtn" data-id="<?= esc($classe['id'], 'attr') ?>" data-nom="<?= esc($classe['nom_classe'], 'attr') ?>" title="Supprimer">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($canAddClasse): ?>
<div class="modal fade" id="addClasseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-4">
            <form method="post" action="<?= base_url('classes/save') ?>">
                <div class="modal-header bg-gradient-primary text-white border-0 rounded-top">
                    <h5 class="modal-title"><i class="fa fa-plus me-2"></i> Créer une classe</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom classe</label>
                            <input type="text" name="nom_classe" class="form-control" value="<?= esc(old('nom_classe')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Code classe</label>
                            <input type="text" name="code_classe" class="form-control" value="<?= esc(old('code_classe')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Structure associée</label>
                            <select name="structure_id" class="form-select">
                                <option value="">Choisir une structure</option>
                                <?php foreach ($structures as $structure): ?>
                                    <option value="<?= esc($structure['id'], 'attr') ?>" <?= old('structure_id') == $structure['id'] ? 'selected' : '' ?>><?= esc($structure['label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Niveau</label>
                            <select name="niveau" class="form-select" required>
                                <option value="">Choisir un niveau</option>
                                <?php foreach (['CI', 'CP', 'CE1', 'CE2'] as $niveau): ?>
                                    <option value="<?= esc($niveau) ?>" <?= old('niveau') === $niveau ? 'selected' : '' ?>><?= esc($niveau) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Langue utilisée</label>
                            <input type="text" name="langue" class="form-control" value="<?= esc(old('langue')) ?>" placeholder="Ex: Français">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date d'ouverture</label>
                            <input type="date" name="date_ouverture" class="form-control" value="<?= esc(old('date_ouverture')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Facilitateur assigné</label>
                            <select name="facilitateur_id" class="form-select">
                                <option value="">Aucun</option>
                                <?php foreach ($facilitateurs as $facilitateur): ?>
                                    <option value="<?= esc($facilitateur['id'], 'attr') ?>" <?= old('facilitateur_id') == $facilitateur['id'] ? 'selected' : '' ?>><?= esc($facilitateur['label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($canEditClasse): ?>
<div class="modal fade" id="editClasseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-4">
            <form method="post" action="<?= base_url('classes/update') ?>">
                <input type="hidden" name="id" id="edit_classe_id">

                <div class="modal-header bg-gradient-warning text-white border-0 rounded-top">
                    <h5 class="modal-title"><i class="fa fa-edit me-2"></i> Modifier la classe</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom classe</label>
                            <input type="text" name="nom_classe" id="edit_nom_classe" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Code classe</label>
                            <input type="text" name="code_classe" id="edit_code_classe" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Structure associée</label>
                            <select name="structure_id" id="edit_structure_id" class="form-select">
                                <option value="">Choisir une structure</option>
                                <?php foreach ($structures as $structure): ?>
                                    <option value="<?= esc($structure['id'], 'attr') ?>"><?= esc($structure['label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Niveau</label>
                            <select name="niveau" id="edit_niveau" class="form-select" required>
                                <option value="">Choisir un niveau</option>
                                <?php foreach (['CI', 'CP', 'CE1', 'CE2'] as $niveau): ?>
                                    <option value="<?= esc($niveau) ?>"><?= esc($niveau) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Langue utilisée</label>
                            <input type="text" name="langue" id="edit_langue" class="form-control" placeholder="Ex: Français">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date d'ouverture</label>
                            <input type="date" name="date_ouverture" id="edit_date_ouverture" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Facilitateur assigné</label>
                            <select name="facilitateur_id" id="edit_facilitateur_id" class="form-select">
                                <option value="">Aucun</option>
                                <?php foreach ($facilitateurs as $facilitateur): ?>
                                    <option value="<?= esc($facilitateur['id'], 'attr') ?>"><?= esc($facilitateur['label']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.editClasseBtn').forEach(function (button) {
            button.addEventListener('click', function () {
                document.getElementById('edit_classe_id').value = this.dataset.id || '';
                document.getElementById('edit_nom_classe').value = this.dataset.nom_classe || '';
                document.getElementById('edit_code_classe').value = this.dataset.code_classe || '';
                document.getElementById('edit_structure_id').value = this.dataset.structure_id || '';
                document.getElementById('edit_niveau').value = this.dataset.niveau || '';
                document.getElementById('edit_langue').value = this.dataset.langue || '';
                document.getElementById('edit_date_ouverture').value = this.dataset.date_ouverture || '';
                document.getElementById('edit_facilitateur_id').value = this.dataset.facilitateur_id || '';

                const editModal = new bootstrap.Modal(document.getElementById('editClasseModal'));
                editModal.show();
            });
        });

        document.querySelectorAll('.deleteClasseBtn').forEach(function (button) {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const nom = this.dataset.nom || 'cette classe';

                if (! confirm('Supprimer la classe "' + nom + '" ?')) {
                    return;
                }

                window.location.href = '<?= base_url('classes/delete/') ?>' + id;
            });
        });
    });
</script>
<?= $this->include('templates/footer') ?>
