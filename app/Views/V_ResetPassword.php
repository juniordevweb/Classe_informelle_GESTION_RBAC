<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Réinitialisation du mot de passe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Classe Passerelles - Education" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon_1.ico') ?>">
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/css/icons.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet" type="text/css" />
    <script src="<?= base_url('assets/js/modernizr.min.js') ?>"></script>

    <style>
        body.password-reset-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at top left, rgba(13, 110, 253, 0.14), transparent 30%),
                radial-gradient(circle at bottom right, rgba(25, 135, 84, 0.12), transparent 28%),
                linear-gradient(135deg, #f5f8fc 0%, #edf2f7 100%);
        }

        .reset-shell {
            width: min(100%, 920px);
            margin: 24px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
            border-radius: 28px;
            overflow: hidden;
            background: #ffffff;
        }

        .reset-hero {
            padding: 32px;
            color: #fff;
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 55%, #198754 100%);
        }

        .reset-hero h1 {
            font-size: clamp(1.6rem, 2.5vw, 2.4rem);
            font-weight: 800;
            margin: 0 0 8px;
        }

        .reset-hero p {
            margin: 0;
            max-width: 58ch;
            color: rgba(255, 255, 255, 0.92);
            line-height: 1.7;
        }

        .reset-body {
            padding: 32px;
        }

        .info-panel {
            border: 1px solid #dbeafe;
            background: #f8fbff;
            border-radius: 18px;
            padding: 18px 20px;
            margin-bottom: 24px;
        }

        .info-panel strong {
            display: block;
            margin-bottom: 6px;
            color: #0f172a;
        }

        .info-panel span {
            color: #475569;
            line-height: 1.7;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
        }

        .input-group-text {
            background: #f8fafc;
        }

        .footer-note {
            color: #64748b;
            font-size: 0.92rem;
        }

        @media (max-width: 768px) {
            .reset-shell {
                margin: 12px;
                border-radius: 22px;
            }

            .reset-hero,
            .reset-body {
                padding: 22px;
            }
        }
    </style>
</head>
<body class="password-reset-page">
<?php
$errorMessage = session()->getFlashdata('error');
$successMessage = session()->getFlashdata('success');
$warningMessage = session()->getFlashdata('warning');
?>

<div class="reset-shell">
    <div class="reset-hero">
        <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
            <div>
                <div class="text-uppercase fw-semibold small opacity-75 mb-2">Sécurité du compte ! Classe Passerelles</div>
                <h1>Réinitialisation obligatoire</h1>
                <p>
                    Bonjour <?= esc($userName ?? 'utilisateur') ?>, vous devez définir votre mot de passe personnel avant d'accéder au système.
                </p>
            </div>
            <div class="text-end">
                <i class="fa fa-lock fa-3x opacity-75"></i>
            </div>
        </div>
    </div>

    <div class="reset-body">
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger"><?= esc($errorMessage) ?></div>
        <?php endif; ?>

        <!-- <?php if ($warningMessage): ?>
            <div class="alert alert-warning"><?= esc($warningMessage) ?></div>
        <?php endif; ?> -->

        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?= esc($successMessage) ?></div>
        <?php endif; ?>

        <div class="info-panel">
            <strong>Remplissez les champs ci-dessous </strong>
            <!-- <span>
                Saisissez votre mot de passe actuel, choisissez un nouveau mot de passe, puis confirmez-le.
                Après validation, vous serez redirigé vers votre espace autorisé.
            </span> -->
        </div>

        <form action="<?= base_url('password/reset') ?>" method="post">
            <?= csrf_field() ?>

            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Mot de passe actuel</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-key"></i></span>
                        <input type="password" name="current_password" class="form-control form-control-lg" placeholder="Entrez le mot de passe actuel" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nouveau mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-shield"></i></span>
                        <input type="password" name="new_password" class="form-control form-control-lg" placeholder="Nouveau mot de passe" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-check-circle"></i></span>
                        <input type="password" name="confirm_password" class="form-control form-control-lg" placeholder="Confirmez le mot de passe" required>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4 flex-wrap">
                <button type="submit" class="btn btn-primary btn-lg px-4">
                    <i class="fa fa-save me-2"></i> Mettre à jour
                </button>
            </div>
        </form>

        <p class="footer-note mt-4 mb-0">
            Ce changement est demandé uniquement lors de la première connexion.
        </p>
    </div>
</div>

<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/detect.js') ?>"></script>
<script src="<?= base_url('assets/js/fastclick.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.slimscroll.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.blockUI.js') ?>"></script>
<script src="<?= base_url('assets/js/waves.js') ?>"></script>
<script src="<?= base_url('assets/js/wow.min.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.nicescroll.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.scrollTo.min.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.app.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if ($errorMessage): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: <?= json_encode($errorMessage) ?>,
            timer: 2800,
            showConfirmButton: false
        });
    </script>
<?php endif; ?>

<?php if ($warningMessage): ?>
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Attention',
            text: <?= json_encode($warningMessage) ?>,
            timer: 3000,
            showConfirmButton: false
        });
    </script>
<?php endif; ?>

<?php if ($successMessage): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Succès',
            text: <?= json_encode($successMessage) ?>,
            timer: 2600,
            showConfirmButton: false
        });
    </script>
<?php endif; ?>
</body>
</html>
