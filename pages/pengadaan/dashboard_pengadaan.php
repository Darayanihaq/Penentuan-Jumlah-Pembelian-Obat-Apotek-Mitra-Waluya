<?php
include '../../config/auth.php';
include '../../config/config.php';
include '../../config/db.php';
include '../../templates/header.php';
onlyPengadaan();

?>

<!-- Wrapper Layout -->
<div class="layout-wrapper">

    <!-- Sidebar -->
    <?php include '../../templates/sidebar_pengadaan.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <div class="px-4 py-4">
            <h2 class="fw-semibold">Beranda</h2>
            <!-- <div class="row g-3 py-2">
                <div class="col-md-3">
                    <div class="card shadow-sm p-3">
                        <h6>Total Obat</h6>
                        <p class="fs-5 fw-semibold mb-0"><?= $totalObat ?> Jenis</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-3">
                        <h6>Total Supplier</h6>
                        <p class="fs-5 fw-semibold mb-0"><?= $totalSupplier ?> Supplier</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-3">
                        <h6>Total Pengguna</h6>
                        <p class="fs-5 fw-semibold mb-0"><?= $totalPengguna ?> User</p>
                    </div>
                </div>
            </div> -->
        </div>
    </main>
</div>

<script src="<?= BASE_URL ?>js/active_menu.js"></script>
</body>

</html>