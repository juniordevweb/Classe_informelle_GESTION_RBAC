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

                                    <!-- Bouton Modifier -->
                                    <a href="#" class="btn btn-sm btn-outline-primary editBtn"
                                       data-id="<?= $r['id'] ?>"
                                       data-nom="<?= $r['nom_role'] ?>"
                                       data-bs-toggle="modal"
                                       data-bs-target="#editProfil">
                                       <i class="fa fa-edit"></i>
                                    </a>

                                    <!-- Bouton Supprimer -->
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
                    <h5 class="modal-title">
                        <i class="fa fa-user-plus me-2"></i> Ajouter Profil
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-4">
                        <label class="form-label fw-bold">Nom du profil</label>
                        <input type="text" name="nom_role" 
                               class="form-control form-control-lg" 
                               placeholder="Ex: DALN"
                               required>
                    </div>

                    <hr>

                    <h6 class="mb-3 text-primary">
                        <i class="fa fa-lock me-2"></i> Attribuer les Permissions
                    </h6>

                    <div class="table-responsive">
                        <table class="table table-bordered text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Menu</th>
                                    <th><span class="badge bg-info">Read</span></th>
                                    <th><span class="badge bg-success">Add</span></th>
                                    <th><span class="badge bg-warning text-dark">Update</span></th>
                                    <th><span class="badge bg-danger">Delete</span></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach($menus as $menu): ?>
                                <tr>
                                    <td class="text-start fw-bold">
                                        <i class="fa fa-folder me-2 text-secondary"></i>
                                        <?= $menu['nom_menu'] ?>
                                    </td>

                                    <?php foreach($permissions as $perm): ?>
                                    <td>
                                        <input type="checkbox"
                                               class="form-check-input"
                                               name="permissions[]"
                                               value="<?= $menu['id'].'|'.$perm['id'] ?>">
                                    </td>
                                    <?php endforeach; ?>

                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Annuler
                    </button>

                    <button class="btn btn-success px-4">
                        <i class="fa fa-save me-1"></i> Enregistrer
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- ================= MODAL EDIT PROFIL ================= -->
<!-- Modal Modifier Profil (Design Pro) -->
<div class="modal fade" id="editProfil" tabindex="-1" aria-labelledby="editProfilLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">

            <form method="post" action="<?= base_url('profils/update') ?>">

                <input type="hidden" name="role_id" id="edit_role_id">

                <!-- Header -->
                <div class="modal-header bg-gradient-warning text-white rounded-top-4">
                    <h5 class="modal-title fw-bold" id="editProfilLabel">
                        <i class="fa fa-edit me-2"></i> Modifier Profil
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- Body -->
                <div class="modal-body py-4 px-5">

                    <div class="mb-4">
                        <label for="edit_nom_role" class="form-label fw-semibold">Nom du Profil</label>
                        <input type="text" name="nom_role" id="edit_nom_role"
                               class="form-control form-control-lg rounded-3" required>
                    </div>

                    <h6 class="mb-3 text-primary fw-semibold">
                        <i class="fa fa-lock me-2"></i> Attribuer les Permissions
                    </h6>

                    <div class="table-responsive rounded-3 shadow-sm">
                        <table class="table table-bordered text-center align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Menu</th>
                                    <th><span class="badge bg-info">Read</span></th>
                                    <th><span class="badge bg-success">Add</span></th>
                                    <th><span class="badge bg-warning text-dark">Update</span></th>
                                    <th><span class="badge bg-danger">Delete</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($menus as $menu): ?>
                                <tr>
                                    <td class="text-start fw-semibold">
                                        <i class="fa fa-folder me-2 text-secondary"></i>
                                        <?= $menu['nom_menu'] ?>
                                    </td>

                                    <?php foreach($permissions as $perm): ?>
                                    <td>
                                        <input type="checkbox"
                                               class="edit-permission form-check-input"
                                               name="permissions[]"
                                               value="<?= $menu['id'].'|'.$perm['id'] ?>">
                                    </td>
                                    <?php endforeach; ?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                </div>

                <!-- Footer -->
                <div class="modal-footer border-0 pt-0 pb-4 px-5">
                    <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-warning btn-lg rounded-3">
                        <i class="fa fa-check me-1"></i> Mettre à jour
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- ================= SCRIPT EDIT & DELETE AJAX ================= -->
<script>
document.addEventListener("DOMContentLoaded", function() {

<?php if(session()->getFlashdata('success_update')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Mis à jour !',
        text: "<?= session()->getFlashdata('success_update') ?>",
        timer: 2000,
        showConfirmButton: false
    });
<?php endif; ?>

<?php if(session()->getFlashdata('success')): ?>
    Swal.fire({
        icon: 'success',
        title: 'Succès !',
        text: "<?= session()->getFlashdata('success') ?>",
        timer: 2000,
        showConfirmButton: false
    });
<?php endif; ?>

});
</script>

<script>

// MODIFIER MODAL
document.querySelectorAll('.editBtn').forEach(button => {
    button.addEventListener('click', function() {
        const roleId = this.dataset.id;
        const roleNom = this.dataset.nom;

        document.getElementById('edit_role_id').value = roleId;
        document.getElementById('edit_nom_role').value = roleNom;

        // Décocher toutes les checkboxes d'abord
        document.querySelectorAll('#editProfil input.edit-permission').forEach(cb => cb.checked = false);

        // Récupérer via fetch les permissions existantes
        fetch("<?= base_url('profils/getProfil/') ?>" + roleId)
            .then(res => res.json())
            .then(data => {
                const perms = data.permissions;

                // Cocher les permissions existantes
                perms.forEach(p => {
                    const selector = `#editProfil input.edit-permission[value="${p.menu_id}|${p.permission_id}"]`;
                    const checkbox = document.querySelector(selector);
                    if (checkbox) checkbox.checked = true;
                });
            });
    });
});

// DELETE BUTTON
document.querySelectorAll('.deleteBtn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();

        let id = this.dataset.id;
        let nom = this.dataset.nom;

        Swal.fire({
            title: 'Supprimer ?',
            html: "Voulez-vous vraiment supprimer : <br><strong>" + nom + "</strong> ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if(result.isConfirmed){
                // AJAX pour supprimer
                fetch("<?= base_url('profils/delete_ajax') ?>/" + id, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success'){
                        Swal.fire('Supprimé !', data.message, 'success');
                        this.closest('tr').remove();
                    } else {
                        Swal.fire('Erreur', data.message, 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Erreur', 'Une erreur est survenue', 'error');
                });
            }
        });
    });
});
</script>

<?= $this->include('templates/footer') ?>