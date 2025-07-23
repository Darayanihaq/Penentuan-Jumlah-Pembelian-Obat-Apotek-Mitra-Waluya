<?php
session_start();
include '../../config/db.php';
include '../../config/config.php';
include '../../templates/header.php';
include '../../config/auth.php';
include '../../components/alert.php';
onlyPengadaan();
?>

<div class="layout-wrapper">
    <!-- Sidebar tetap -->
    <?php include '../../templates/sidebar_pengadaan.php'; ?>

    <!-- Main Content (scrollable) -->
    <main class="main-content">
        <div class="px-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 mb-0">Data Stok Obat</h2>
                <div class="search-container" <div class="search-box">
                    <input type="text" id="searchInput" class="search-input"
                        onkeyup="searchTable('searchInput', 'stokTableBody')" placeholder="Cari...">
                </div>
            </div>
            <?php include '../../modules/stok/table.php'; ?>
        </div>
</div>
</main>

</div>

<script src="<?= BASE_URL ?>js/active_menu.js"></script>
<script src="<?= BASE_URL ?>js/searchtable.js"></script>
</body>

</html>