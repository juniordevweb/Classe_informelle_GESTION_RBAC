<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8" />
        <title>Classe Passerelles - Education</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <link rel="shortcut icon" href="<?= base_url('assets/images/favicon_1.ico')?>">

        <!-- Custom Files -->
        <link href="<?= base_url('assets/css/bootstrap.min.css')?>" rel="stylesheet" type="text/css" />
        <link href="<?= base_url('assets/css/icons.css')?>" rel="stylesheet" type="text/css" />
        <link href="<?= base_url('assets/css/style.css')?>" rel="stylesheet" type="text/css" />

        <script src="<?= base_url('assets/js/modernizr.min.js')?>"></script>

        
    </head>
    <body>


        <div class="wrapper-page">
            <div class="card card-pages">
                <div class="card-header bg-img"> 
                    <div class="bg-overlay"></div>
                    <h3 class="text-center m-t-10 text-white"> <strong>Connexion</strong> </h3>
                </div> 
                <?php if (session()->getFlashdata('error')): ?>
                <p style="color:red"><?= session()->getFlashdata('error') ?></p>
                <?php endif; ?>
                <div class="card-body">
                <form class="form-horizontal m-t-20" action="<?= base_url('login/process') ?>" method="post">
    
    <?= csrf_field(); ?>

    <div class="form-group">
        <div class="col-12">
            <input class="form-control input-lg" 
                   type="email" 
                   name="email" 
                   required 
                   placeholder="Email">
        </div>
    </div>

    <div class="form-group">
        <div class="col-12">
            <input class="form-control input-lg" 
                   type="password" 
                   name="password" 
                   required 
                   placeholder="Password">
        </div>
    </div>

    <div class="form-group text-center m-t-40">
        <div class="col-12">
            <button class="btn btn-primary  btn-lg w-lg " 
                    type="submit">
                se connecter
            </button>
        </div>
    </div>

</form> 
                </div>                                 
                
            </div>
        </div>

        
    	<script>
            var resizefunc = [];
        </script>

        <!-- Main  -->
        <script src="<?=base_url('assets/js/jquery.min.js')?>"></script>
        <script src="<?=base_url('assets/js/bootstrap.bundle.min.js')?>"></script>
        <script src="<?=base_url('assets/js/detect.js')?>"></script>
        <script src="<?=base_url('assets/js/fastclick.js')?>"></script>
        <script src="<?=base_url('assets/js/jquery.slimscroll.js')?>"></script>
        <script src="<?=base_url('assets/js/jquery.blockUI.js')?>"></script>
        <script src="<?=base_url('assets/js/waves.js')?>"></script>
        <script src="<?=base_url('assets/js/wow.min.js')?>"></script>
        <script src="<?=base_url('assets/js/jquery.nicescroll.js')?>"></script>
        <script src="<?=base_url('assets/js/jquery.scrollTo.min.js')?>"></script>

        <script src="<?=base_url('assets/js/jquery.app.js')?>"></script>
	
	</body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if(session()->getFlashdata('error')): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Erreur',
    text: "<?= session()->getFlashdata('error') ?>",
    timer: 2500,
    showConfirmButton: false
});
</script>
<?php endif; ?>

<?php if(session()->getFlashdata('success')): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Succès',
    text: "<?= session()->getFlashdata('success') ?>",
    timer: 2500,
    showConfirmButton: false
});
</script>
<?php endif; ?>
</html>