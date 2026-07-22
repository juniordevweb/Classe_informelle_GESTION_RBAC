<?= $this->include('templates/header') ?>
<?= $this->include('templates/top_bar') ?>
<?= $this->include('templates/left_sidebar') ?>

<?php
$user_permissions = $user_permissions ?? [];

$hasUserActionPermission = static function (array $permissions, int $menuId, int $sousMenuId, int $permissionId): bool {
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

$canAddUser = $hasUserActionPermission($user_permissions, 6, 6, 2);
$canEditUser = $hasUserActionPermission($user_permissions, 6, 6, 3);
$canDeleteUser = $hasUserActionPermission($user_permissions, 6, 6, 4);

$showUserActionsColumn = $canEditUser || $canDeleteUser;
?>

<style>
    #addUserModal .modal-dialog {
        max-width: 900px;
    }

    #addUserModal .modal-content {
        max-height: calc(100vh - 2rem);
        padding: 0;
    }

    #addUserModal form {
        display: flex;
        flex-direction: column;
        min-height: 0;
        max-height: calc(100vh - 2rem);
    }

    #addUserModal .modal-body {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto;
        padding: 1.25rem !important;
    }

    #addUserModal .modal-footer {
        flex-shrink: 0;
    }

    @media (max-width: 768px) {
        #addUserModal .modal-dialog {
            max-width: calc(100vw - 1rem);
            margin: 0.5rem auto;
        }

        #addUserModal .modal-body,
        #addUserModal .modal-header,
        #addUserModal .modal-footer {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        #addUserModal .modal-footer {
            gap: 0.75rem;
        }

        #addUserModal .modal-footer .btn {
            width: 100%;
        }
    }
</style>

<br>

<div class="content-page">
    <div class="container mt-4">

        <br><br><br>

        <div class="d-flex justify-content-between mb-4 ">
            <h3>Gestion des Utilisateurs</h3>

            <?php if ($canAddUser): ?>
                <button class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#addUserModal">
                    Ajouter Utilisateur
                </button>
            <?php endif; ?>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Profil</th>

                    <?php if ($showUserActionsColumn): ?>
                        <th width="150">Action</th>
                    <?php endif; ?>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= esc($user['nom']) ?></td>
                        <td><?= esc($user['email']) ?></td>

                        <td>
                            <?php
                            $role = array_filter(
                                $profils,
                                fn ($p) => $p['id'] == $user['role_id']
                            );

                            echo $role ? esc(array_values($role)[0]['nom_role']) : '';
                            ?>
                        </td>

                        <?php if ($showUserActionsColumn): ?>
                            <td class="text-center">

                                <?php if ($canEditUser): ?>
                                    <button class="btn btn-outline-success btn-sm blockBtn"
                                            data-id="<?= esc($user['id']) ?>"
                                            data-nom="<?= esc($user['nom']) ?>"
                                            title="Bloquer">
                                        <i class="fa fa-user"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if ($canEditUser): ?>
                                    <button class="btn btn-outline-primary btn-sm editBtn"
                                            data-id="<?= esc($user['id']) ?>"
                                            data-nom="<?= esc($user['nom']) ?>"
                                            data-email="<?= esc($user['email']) ?>"
                                            data-role="<?= esc($user['role_id']) ?>"
                                            title="Modifier">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                <?php endif; ?>

                                <?php if ($canDeleteUser): ?>
                                    <button class="btn btn-outline-danger btn-sm deleteBtn"
                                            data-id="<?= esc($user['id']) ?>"
                                            data-nom="<?= esc($user['nom']) ?>"
                                            title="Supprimer">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                <?php endif; ?>

                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>


<!-- ================= ADD USER MODAL ================= -->

<?php if ($canAddUser): ?>

<div class="modal fade"
     id="addUserModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <form method="post"
                  action="<?= base_url('users/save_user') ?>">

                <?= csrf_field() ?>

                <!-- HEADER -->
                <div class="modal-header border-0 px-4 py-3"
                     style="background: linear-gradient(135deg,#0d6efd,#0b5ed7);">

                    <div>
                        <h4 class="modal-title text-white fw-bold mb-1">
                            <i class="fa-solid fa-user-plus me-2"></i>
                            Ajouter un utilisateur
                        </h4>

                        <small class="text-white-50">
                            Création d’un nouveau compte utilisateur
                        </small>
                    </div>

                    <button type="button"
                            class="btn-close btn-close-white shadow-none"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body bg-light p-4">

                    <div class="row g-4">

                        <!-- LEFT -->
                        <div class="col-lg-5">

                            <!-- RECHERCHE -->
                            <div class="card border-0 shadow-sm rounded-4 h-100">

                                <div class="card-body p-4">

                                    <div class="d-flex align-items-center mb-4">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center"
                                             style="width:50px;height:50px;">
                                            <i class="fa-solid fa-magnifying-glass fs-5"></i>
                                        </div>

                                        <div class="ms-3">
                                            <h5 class="fw-bold mb-0">
                                                Recherche utilisateur
                                            </h5>

                                            <small class="text-muted">
                                                INE ou email professionnel
                                            </small>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            INE / Email
                                        </label>

                                        <div class="input-group input-group-lg">

                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fa fa-search text-muted"></i>
                                            </span>

                                            <input type="text"
                                                   id="searchUser"
                                                   class="form-control border-start-0 shadow-none"
                                                   placeholder="Ex: 64G17Y12">

                                        </div>
                                    </div>

                                    <button type="button"
                                            class="btn btn-primary btn-lg w-100 rounded-3"
                                            id="btnSearchUser">

                                        <i class="fa fa-search me-2"></i>
                                        Rechercher l'utilisateur
                                    </button>

                                    <button type="button"
                                            class="btn btn-outline-secondary btn-lg w-100 rounded-3 mt-2"
                                            id="btnManualUser">

                                        <i class="fa fa-pen me-2"></i>
                                        Saisie manuelle
                                    </button>

                                    <div class="alert alert-light border mt-4 mb-0 rounded-3">
                                        <small class="text-muted">
                                            <i class="fa fa-circle-info me-1"></i>
                                            Les informations peuvent être remplies automatiquement ou saisies manuellement.
                                        </small>
                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- RIGHT -->
                        <div class="col-lg-7">

                            <div class="card border-0 shadow-sm rounded-4">

                                <div class="card-body p-4">

                                    <!-- TITLE -->
                                    <div class="d-flex align-items-center mb-4">

                                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center"
                                             style="width:50px;height:50px;">
                                            <i class="fa-solid fa-id-badge fs-5"></i>
                                        </div>

                                        <div class="ms-3">
                                            <h5 class="fw-bold mb-0">
                                                Informations utilisateur
                                            </h5>

                                            <small class="text-muted">
                                                Données du compte
                                            </small>
                                        </div>

                                    </div>

                                    <!-- INFOS -->
                                    <div class="row">

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">
                                                Nom
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text bg-white">
                                                    <i class="fa fa-user text-primary"></i>
                                                </span>

                                                <input type="text"
                                               name="nom"
                                               class="form-control"
                                               id="userNom"
                                               placeholder="Nom"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">
                                                Prénom
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text bg-white">
                                                    <i class="fa fa-user text-primary"></i>
                                                </span>

                                                <input type="text"
                                               name="prenom"
                                               class="form-control"
                                               id="userPrenom"
                                               placeholder="Prénom"
                                                       required>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">
                                                INE
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text bg-white">
                                                    <i class="fa fa-id-card text-primary"></i>
                                                </span>

                                                <input type="text"
                                               name="ine"
                                               class="form-control"
                                               id="userIne"
                                               placeholder="INE"
                                                       required>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-semibold">
                                                Email professionnel
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text bg-white">
                                                    <i class="fa fa-envelope text-primary"></i>
                                                </span>

                                                <input type="email"
                                               name="email"
                                               class="form-control"
                                               id="userEmail"
                                               placeholder="Mail professionnel"
                                                       required>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- ROLE -->
                                    <div class="mb-3">

                                        <label class="form-label fw-semibold">
                                            Profil utilisateur
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text bg-white">
                                                <i class="fa fa-shield-alt text-primary"></i>
                                            </span>

                                            <select name="role_id"
                                                    class="form-select"
                                                    id="userRole"
                                                    required>

                                                <option value="">
                                                    Sélectionner un profil
                                                </option>

                                                <?php foreach ($profils as $p): ?>
                                                    <option value="<?= esc($p['id']) ?>">
                                                        <?= esc($p['nom_role']) ?>
                                                    </option>
                                                <?php endforeach; ?>

                                            </select>

                                        </div>

                                    </div>

                                    <!-- PASSWORD -->
                                    <div class="mb-2">

                                        <label class="form-label fw-semibold">
                                            Mot de passe généré
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text bg-white">
                                                <i class="fa fa-lock text-primary"></i>
                                            </span>

                                            <input type="password"
                                                   name="password"
                                                   class="form-control"
                                                   id="userPassword"
                                                   readonly
                                                   required>

                                            <button type="button"
                                                    class="btn btn-outline-primary"
                                                    id="generatePassword">

                                                <i class="fa fa-rotate me-1"></i>
                                                Régénérer
                                            </button>

                                        </div>

                                        <small class="text-muted">
                                            .
                                        </small>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer border-0 bg-white px-4 py-3">

                    <button type="button"
                            class="btn btn-light border rounded-3 px-4"
                            data-bs-dismiss="modal">

                        <i class="fa fa-times me-1"></i>
                        Annuler
                    </button>

                   <!-- // <button type="submit"
                            name="submit_action"
                            value="save"
                            class="btn btn-success rounded-3 px-4 shadow-sm"
                            id="btnSaveUser"
                            disabled>

                        <i class="fa fa-save me-1"></i>
                        Enregistrer l'utilisateur
                    </button> -->

                    <button type="submit"
                            name="submit_action"
                            value="save_and_send"
                            class="btn btn-primary rounded-3 px-4 shadow-sm"
                            id="btnSaveAndSendUser"
                            disabled>

                        <i class="fa fa-paper-plane me-1"></i>
                        Enregistrer et envoyer
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>

<?php endif; ?>


<!-- ================= EDIT USER MODAL ================= -->

<?php if ($canEditUser): ?>

<div class="modal fade" id="editUserModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <form method="post"
                  action="<?= base_url('users/update') ?>">

                <?= csrf_field() ?>

                <div class="modal-header bg-info text-white">
                    <h5>Modifier Utilisateur</h5>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden"
                           name="id"
                           id="edit_id">

                    <input type="text"
                           name="nom"
                           id="edit_nom"
                           class="form-control mb-3"
                           placeholder="Nom"
                           required>

                    <input type="email"
                           name="email"
                           id="edit_email"
                           class="form-control mb-3"
                           placeholder="Email"
                           required>

                    <select name="role_id"
                            id="edit_role"
                            class="form-select mb-3"
                            required>

                        <?php foreach ($profils as $p): ?>
                            <option value="<?= esc($p['id']) ?>">
                                <?= esc($p['nom_role']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Annuler
                    </button>

                    <button type="submit"
                            class="btn btn-info">
                        Mettre à jour
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<?php endif; ?>


<script>

// ================= GENERATE PASSWORD =================

function genererPassword(length = 12) {
    const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$%";
    let password = "";

    for (let i = 0; i < length; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }

    return password;
}


// ================= RESET ADD USER FORM =================

function resetAddUserForm() {
    const fields = [
        'searchUser',
        'userNom',
        'userPrenom',
        'userIne',
        'userEmail'
    ];

    fields.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.value = '';
        }
    });

    const role = document.getElementById('userRole');
    if (role) {
        role.value = '';
    }

    const password = document.getElementById('userPassword');
    if (password) {
        password.value = genererPassword();
    }

    const btnSave = document.getElementById('btnSaveUser');
    if (btnSave) {
        btnSave.disabled = true;
    }

    const btnSaveAndSend = document.getElementById('btnSaveAndSendUser');
    if (btnSaveAndSend) {
        btnSaveAndSend.disabled = true;
    }
}


// ================= OPEN ADD MODAL =================

const addModal = document.getElementById('addUserModal');

if (addModal) {
    addModal.addEventListener('show.bs.modal', function () {
        resetAddUserForm();
    });
}


// ================= BTN GENERATE PASSWORD =================

const generateBtn = document.getElementById('generatePassword');

if (generateBtn) {
    generateBtn.addEventListener('click', function () {
        document.getElementById('userPassword').value = genererPassword();
    });
}


// ================= EDIT USER =================

document.querySelectorAll('.editBtn').forEach(button => {
    button.addEventListener('click', function () {

        document.getElementById('edit_id').value = this.dataset.id;
        document.getElementById('edit_nom').value = this.dataset.nom;
        document.getElementById('edit_email').value = this.dataset.email;
        document.getElementById('edit_role').value = this.dataset.role;

        let modal = new bootstrap.Modal(
            document.getElementById('editUserModal')
        );

        modal.show();

    });
});




// ================= BLOCK USER =================

document.querySelectorAll('.blockBtn').forEach(button => {
    button.addEventListener('click', function () {

        let id = this.dataset.id;
        let nom = this.dataset.nom;

        Swal.fire({
            title: 'Bloquer utilisateur ?',
            html: "Voulez-vous bloquer : <br><strong>" + nom + "</strong> ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f0ad4e',
            cancelButtonText: 'Annuler',
            confirmButtonText: 'Oui, bloquer'
        }).then((result) => {
            if (result.isConfirmed) {
                submitPostAction("<?= base_url('users/block/') ?>" + id);
            }
        });

    });
});


// ================= DELETE USER =================

document.querySelectorAll('.deleteBtn').forEach(button => {
    button.addEventListener('click', function () {

        let id = this.dataset.id;
        let nom = this.dataset.nom;

        Swal.fire({
            title: 'Supprimer utilisateur ?',
            html: "Cette action est irréversible ! <br><strong>" + nom + "</strong>",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonText: 'Annuler',
            confirmButtonText: 'Oui, supprimer'
        }).then((result) => {
            if (result.isConfirmed) {
                submitPostAction("<?= base_url('users/delete/') ?>" + id);
            }
        });

    });
});

</script>

<?php
$flashSuccess = session()->getFlashdata('success');
$flashError = session()->getFlashdata('error');
$flashDelete = session()->getFlashdata('success_delete');
?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    <?php if ($flashSuccess): ?>
    Swal.fire({
        icon: 'success',
        title: 'Succès',
        text: <?= json_encode($flashSuccess) ?>,
        confirmButtonColor: '#198754'
    });
    <?php endif; ?>

    <?php if ($flashDelete): ?>
    Swal.fire({
        icon: 'success',
        title: 'Succès',
        text: <?= json_encode($flashDelete) ?>,
        confirmButtonColor: '#198754'
    });
    <?php endif; ?>

    <?php if ($flashError): ?>
    Swal.fire({
        icon: 'error',
        title: 'Erreur',
        text: <?= json_encode($flashError) ?>,
        confirmButtonColor: '#d33'
    });
    <?php endif; ?>
});
</script>

<?= $this->include('templates/footer') ?>
