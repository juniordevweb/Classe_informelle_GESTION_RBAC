<?= $this->include('templates/header') ?>

<!-- Begin page -->
<div id="wrapper">
    <?= $this->include('templates/top_bar') ?>
    <?= $this->include('templates/left_sidebar') ?>

    <main>
        <?= $this->renderSection('content') ?>
    </main>
</div>

<!-- END wrapper -->
<?= $this->include('templates/footer') ?>
