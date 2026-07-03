<footer class="footer">
    2016 - 2019 © Moltran.
</footer>
            
<script>
    var resizefunc = [];
</script>

<!-- jQuery  -->
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
            
<!-- sweet alerts -->
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Todoapp -->
<script src="<?=base_url('assets/pages/jquery.todo.js')?>"></script>
            
<!-- jQuery  -->
<script src="<?=base_url('assets/pages/jquery.chat.js')?>"></script>
            
<!-- App js  -->
<script src="<?=base_url('assets/js/jquery.app.js')?>"></script>
            
<script>
/* ==============================================
Counter Up
=============================================== */
    jQuery(document).ready(function($) {
    if ($.fn.counterUp) {
        $('.counter').counterUp({
            delay: 100,
            time: 1200
        });
    }
});
                
</script>
<script>
    const BASE_URL = "<?= base_url() ?>";
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        <?php if (session()->getFlashdata('access_denied')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Acces refuse',
                text: "<?= esc(session()->getFlashdata('access_denied'), 'js') ?>",
                confirmButtonColor: '#d33'
            });
        <?php endif; ?>
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="<?= base_url('assets/js/ien-search.js') ?>"></script>
<script src="<?= base_url('assets/js/menu.js') ?>"></script>
<script src="<?= base_url('assets/js/sousmenu.js') ?>"></script>
<script src="<?= base_url('assets/js/profil.js') ?>"></script>
<script src="<?= base_url('assets/js/utilisateurs.js') ?>"></script>

</body>
</html>
