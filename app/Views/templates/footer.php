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
    const CSRF_TOKEN_NAME = <?= json_encode(csrf_token()) ?>;
    const CSRF_TOKEN_HASH = <?= json_encode(csrf_hash()) ?>;

    window.submitPostAction = function (url, extraFields = {}) {
        const form = document.createElement('form');
        form.method = 'post';
        form.action = url;
        form.style.display = 'none';

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = CSRF_TOKEN_NAME;
        csrfInput.value = CSRF_TOKEN_HASH;
        form.appendChild(csrfInput);

        Object.entries(extraFields).forEach(function ([name, value]) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    };
</script>
<script>
    const flashSwalSuccess = <?= json_encode(session()->getFlashdata('swal_success')) ?>;
    const flashAccessDenied = <?= json_encode(session()->getFlashdata('access_denied')) ?>;

    document.addEventListener('DOMContentLoaded', function () {
        if (flashSwalSuccess) {
            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: flashSwalSuccess,
                confirmButtonColor: '#28a745'
            });
        }

        if (flashAccessDenied) {
            Swal.fire({
                icon: 'error',
                title: 'Acces refuse',
                text: flashAccessDenied,
                confirmButtonColor: '#d33'
            });
        }
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
