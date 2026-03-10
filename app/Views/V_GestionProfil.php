<?= $this->include('templates/header') ?>
<?= $this->include('templates/top_bar') ?>
<?= $this->include('templates/left_sidebar') ?><br><br><br>

<div class="content-page">
    <div class="container-fluid mt-4">

        <div class="card shadow-sm border-0">
            <div class="card-header text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fa fa-user-shield me-2"></i> Gestion des Profils
                </h4>
                <button data-bs-toggle="modal" data-bs-target="#addProfil" 
                        class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Ajouter Profil
                </button>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle table-striped">
                        <thead class="table-light">
                            <tr>
                                <th width="80">#</th>
                                <th>Nom Profil</th>
                                <th width="150" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($roles as $r): ?>
                            <tr>
                                <td><strong><?= $r['id'] ?></strong></td>
                                <td>
                                    <span class="badge bg-secondary p-2">
                                        <?= $r['nom_role'] ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-sm btn-outline-primary editBtn"
                                       data-id="<?= $r['id'] ?>"
                                       data-nom="<?= $r['nom_role'] ?>"
                                       data-bs-toggle="modal"
                                       data-bs-target="#editProfil">
                                       <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-danger deleteBtn"
                                       data-id="<?= $r['id'] ?>"
                                       data-nom="<?= $r['nom_role'] ?>">
                                       <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ================= MODAL AJOUT PROFIL ================= -->
<div class="modal fade" id="addProfil">
    <div class="modal-dialog modal-xl">
        <div class="modal-content shadow">
            <form method="post" action="<?= base_url('profils/save') ?>">
                <div class="modal-header text-white">
                    <h5 class="modal-title"><i class="fa fa-user-plus me-2"></i> Ajouter Profil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nom du profil</label>
                        <input type="text" name="nom_role" class="form-control form-control-lg" placeholder="Ex: DALN" required>
                    </div>
                    <hr>
                    <div class="row">
                        <!-- MENUS -->
                        <div class="col-md-4">
                            <ul class="list-group">
                                <?php foreach($menus as $menu): ?>
                                <li class="list-group-item menu-item fw-bold" data-menu="<?= $menu['id'] ?>">
                                    <i class="fa fa-folder me-2"></i> <?= $menu['nom_menu'] ?>
                                </li>
                                <ul class="list-group sous-menu d-none" id="add_menu_<?= $menu['id'] ?>">
                                    <?php if(!empty($menu['sous_menus'])): ?>
                                        <?php foreach($menu['sous_menus'] as $sm): ?>
                                        <li class="list-group-item sousMenuItem" data-menu="<?= $menu['id'] ?>" data-sousmenu="<?= $sm['id'] ?>">
                                            <i class="fa fa-angle-right me-2"></i> <?= $sm['nom_sous_menu'] ?>
                                        </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="list-group-item sousMenuItem" data-menu="<?= $menu['id'] ?>" data-sousmenu="<?= $menu['id'] ?>">
                                            <i class="fa fa-angle-right me-2"></i> <?= $menu['nom_menu'] ?>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <!-- PERMISSIONS -->
                        <div class="col-md-8">
                            <div id="addPermissionBox" class="d-none">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sous menu</th>
                                            <th><span class="badge bg-info">Read</span></th>
                                            <th><span class="badge bg-success">Add</span></th>
                                            <th><span class="badge bg-warning text-dark">Update</span></th>
                                            <th><span class="badge bg-danger">Delete</span></th>
                                        </tr>
                                    </thead>
                                    <tbody id="addPermissionContent"></tbody>
                                </table>
                            </div>
                            <div id="addPermissionMessage" class="alert alert-info">
                                Cliquez sur un sous menu pour afficher les permissions
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button class="btn btn-success px-4"><i class="fa fa-save me-1"></i> Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= MODAL EDIT PROFIL ================= -->
<div class="modal fade" id="editProfil" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <form method="post" action="<?= base_url('profils/update') ?>">
                <input type="hidden" name="role_id" id="edit_role_id">
                <div class="modal-header bg-gradient-warning text-white">
                    <h5 class="modal-title fw-bold"><i class="fa fa-edit me-2"></i> Modifier Profil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-4">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Nom du Profil</label>
                        <input type="text" name="nom_role" id="edit_nom_role" class="form-control form-control-lg" required>
                    </div>
                    <hr>
                    <div class="row">
                        <!-- MENUS -->
                        <div class="col-md-4">
                            <ul class="list-group">
                                <?php foreach($menus as $menu): ?>
                                <li class="list-group-item menu-item fw-bold" data-menu="<?= $menu['id'] ?>">
                                    <i class="fa fa-folder me-2"></i> <?= $menu['nom_menu'] ?>
                                </li>
                                <ul class="list-group sous-menu d-none" id="edit_menu_<?= $menu['id'] ?>">
                                    <?php if(!empty($menu['sous_menus'])): ?>
                                        <?php foreach($menu['sous_menus'] as $sm): ?>
                                        <li class="list-group-item sousMenuItem" data-menu="<?= $menu['id'] ?>" data-sousmenu="<?= $sm['id'] ?>">
                                            <i class="fa fa-angle-right me-2"></i> <?= $sm['nom_sous_menu'] ?>
                                        </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="list-group-item sousMenuItem" data-menu="<?= $menu['id'] ?>" data-sousmenu="<?= $menu['id'] ?>">
                                            <i class="fa fa-angle-right me-2"></i> <?= $menu['nom_menu'] ?>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <!-- PERMISSIONS -->
                        <div class="col-md-8">
                            <div id="editPermissionBox" class="d-none">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sous menu</th>
                                            <th>Read</th>
                                            <th>Add</th>
                                            <th>Update</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody id="editPermissionContent"></tbody>
                                </table>
                            </div>
                            <div id="editPermissionMessage" class="alert alert-info">
                                Cliquez sur un sous menu pour afficher les permissions
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-5">
                    <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning btn-lg rounded-3"><i class="fa fa-check me-1"></i> Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ================= SCRIPT GLOBAL ================= -->
<script>
document.addEventListener("DOMContentLoaded", function() {

    // SweetAlert success
    <?php if(session()->getFlashdata('success')): ?>
    Swal.fire({icon:'success', title:'Succès !', text:"<?= session()->getFlashdata('success') ?>", timer:2000, showConfirmButton:false});
    <?php endif; ?>
    <?php if(session()->getFlashdata('success_update')): ?>
    Swal.fire({icon:'success', title:'Mis à jour !', text:"<?= session()->getFlashdata('success_update') ?>", timer:2000, showConfirmButton:false});
    <?php endif; ?>

    // Permissions render function
  function renderPermissions(label, menuId, sousMenuId, permissions, currentRolePerms) {
    // On crée une seule ligne pour le sous-menu
    let row = `<tr>
        <td class="text-start fw-bold">${label}</td>
        <td><input type="checkbox" class="edit-permission" name="permissions[]" value="${menuId}|${sousMenuId}|1" ${isChecked(menuId, sousMenuId, 1, currentRolePerms)}></td>
        <td><input type="checkbox" class="edit-permission" name="permissions[]" value="${menuId}|${sousMenuId}|2" ${isChecked(menuId, sousMenuId, 2, currentRolePerms)}></td>
        <td><input type="checkbox" class="edit-permission" name="permissions[]" value="${menuId}|${sousMenuId}|3" ${isChecked(menuId, sousMenuId, 3, currentRolePerms)}></td>
        <td><input type="checkbox" class="edit-permission" name="permissions[]" value="${menuId}|${sousMenuId}|4" ${isChecked(menuId, sousMenuId, 4, currentRolePerms)}></td>
    </tr>`;
    return row;
}

// Helper pour cocher les permissions existantes
function isChecked(menuId, sousMenuId, permId, currentRolePerms) {
    return currentRolePerms.some(p => p.menu_id == menuId && p.sous_menu_id == sousMenuId && p.permission_id == permId) ? 'checked' : '';
}

    // ----------------- Add Modal -----------------
    let addPermBox = document.getElementById('addPermissionBox');
    let addPermContent = document.getElementById('addPermissionContent');
    document.querySelectorAll("#addProfil .menu-item").forEach(menu => {
        menu.addEventListener('click', function() {
            let id = this.dataset.menu;
            document.querySelectorAll("#addProfil .sous-menu").forEach(sm => sm.classList.add('d-none'));
            document.getElementById('add_menu_'+id).classList.remove('d-none');
        });
    });
    document.querySelectorAll("#addProfil .sousMenuItem").forEach(sm => {
        sm.addEventListener('click', function() {
            const menuId = this.dataset.menu;
            const sousMenuId = this.dataset.sousmenu;
            const label = this.innerText.trim();
            addPermBox.classList.remove('d-none');
            document.getElementById('addPermissionMessage').classList.add('d-none');
            addPermContent.innerHTML = `
                <tr>
                    <td class="text-start fw-bold">${label}</td>
                    <td><input type="checkbox" name="permissions[]" value="${menuId}|${sousMenuId}|1"></td>
                    <td><input type="checkbox" name="permissions[]" value="${menuId}|${sousMenuId}|2"></td>
                    <td><input type="checkbox" name="permissions[]" value="${menuId}|${sousMenuId}|3"></td>
                    <td><input type="checkbox" name="permissions[]" value="${menuId}|${sousMenuId}|4"></td>
                </tr>
            `;
        });
    });

    // ----------------- Edit Modal -----------------
    let currentRolePerms = [];
    document.querySelectorAll('.editBtn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const roleId = this.dataset.id;
            const roleNom = this.dataset.nom;
            const modal = document.getElementById('editProfil');
            modal.querySelector('#edit_role_id').value = roleId;
            modal.querySelector('#edit_nom_role').value = roleNom;

            const res = await fetch(`<?= base_url('profils/getProfil/') ?>${roleId}`);
            const data = await res.json();
            currentRolePerms = data.permissions || [];
        });
    });

    document.querySelectorAll("#editProfil .menu-item").forEach(menu => {
        menu.addEventListener('click', function() {
            const id = this.dataset.menu;
            document.querySelectorAll("#editProfil .sous-menu").forEach(sm => sm.classList.add('d-none'));
            document.getElementById('edit_menu_'+id).classList.toggle('d-none');
        });
    });

    document.querySelectorAll("#editProfil .sousMenuItem").forEach(sm => {
        sm.addEventListener('click', function() {
            const menuId = this.dataset.menu;
            const sousMenuId = this.dataset.sousmenu;
            const label = this.innerText.trim();
            document.getElementById('editPermissionBox').classList.remove('d-none');
            document.getElementById('editPermissionMessage').classList.add('d-none');
            document.getElementById('editPermissionContent').innerHTML = renderPermissions(label, menuId, sousMenuId, <?= json_encode($permissions) ?>, currentRolePerms);
        });
    });

    // ----------------- DELETE -----------------
    document.querySelectorAll('.deleteBtn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.dataset.id;
            const nom = this.dataset.nom;

            Swal.fire({
                title: 'Supprimer ?',
                html: `Voulez-vous vraiment supprimer : <br><strong>${nom}</strong> ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then(result => {
                if(result.isConfirmed){
                    fetch("<?= base_url('profils/delete_ajax') ?>/"+id, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    }).then(res => res.json())
                    .then(data => {
                        if(data.status === 'success'){
                            Swal.fire('Supprimé !', data.message, 'success');
                            this.closest('tr').remove();
                        } else {
                            Swal.fire('Erreur', data.message, 'error');
                        }
                    }).catch(() => Swal.fire('Erreur', 'Une erreur est survenue', 'error'));
                }
            });
        });
    });

});
</script>

<?= $this->include('templates/footer') ?>