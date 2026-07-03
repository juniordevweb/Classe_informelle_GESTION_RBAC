<?= $this->include('templates/header') ?>
<?= $this->include('templates/top_bar') ?>
<?= $this->include('templates/left_sidebar') ?>
<?php
$pageTitle = $pageTitle ?? 'Page dynamique';
$pageSubtitle = $pageSubtitle ?? '';
$pageUrl = $pageUrl ?? '';
$pageType = $pageType ?? 'Menu';
?>

<br><br><br>

<div class="content-page">
    <div class="container-fluid mt-4">
        <div class="card shadow-sm border-0">
            <div class="card-header text-white">
                <h4 class="mb-0">
                    <i class="fa fa-file-text-o me-2"></i> <?= esc($pageTitle) ?>
                </h4>
                <?php if ($pageSubtitle !== ''): ?>
                    <small class="text-white-50"><?= esc($pageSubtitle) ?></small>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    Route générée automatiquement pour un <?= esc(strtolower($pageType)) ?>.
                </div>

                <dl class="row mb-0">
                    <dt class="col-sm-3">Type</dt>
                    <dd class="col-sm-9"><?= esc($pageType) ?></dd>

                    <dt class="col-sm-3">URL</dt>
                    <dd class="col-sm-9"><?= esc($pageUrl !== '' ? '/' . $pageUrl : '-') ?></dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<?= $this->include('templates/footer') ?>
