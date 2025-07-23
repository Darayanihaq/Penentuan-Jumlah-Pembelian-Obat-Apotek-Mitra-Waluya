<?php if (isset($_SESSION['alert'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: '<?= $_SESSION['alert']['type'] === 'success' ? 'Berhasil' : 'Gagal' ?>',
            text: '<?= $_SESSION['alert']['message'] ?>',
            confirmButtonColor: '#4a90e2'
        });
    </script>
    <?php unset($_SESSION['alert']); ?>
<?php endif; ?>