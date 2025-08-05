<?php
session_start();
include '../../config/db.php';
include '../../config/config.php';
include '../../templates/header.php';
include '../../config/auth.php';
include '../../components/alert.php';
include '../../modules/stok/update_stok.php';
onlyApoteker();
?>

<div class="layout-wrapper">
    <?php include '../../templates/sidebar_apoteker.php'; ?>

    <main class="main-content">
        <div class="px-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="h4 fw-semibold mb-0">Data Stok Obat</h4>
                <div class="filter-container">
                    <select id="statusFilter" class="form-select" onchange="filterTableByStatus()">
                        <option value="">Semua Status</option>
                        <option value="kosong">Kosong</option>
                        <option value="stok rendah">Stok Rendah</option>
                        <option value="kedaluwarsa">Kedaluwarsa</option>
                        <option value="tersedia">Tersedia</option>
                    </select>
                </div>
            </div>
            <?php include '../../modules/stok/table.php'; ?>
        </div>
</div>
</main>

</div>

<script src="<?= BASE_URL ?>js/stok_table_filter.js"></script>
<script src="<?= BASE_URL ?>js/active_menu.js"></script>
<script src="<?= BASE_URL ?>js/searchtable.js"></script>
</body>

</html>