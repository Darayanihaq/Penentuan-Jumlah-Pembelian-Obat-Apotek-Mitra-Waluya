<?php
session_start();
require_once '../../config/db.php';
require_once '../../config/config.php';
require_once '../../config/auth.php';
include '../../templates/header.php';
onlyApoteker();
?>

<div class="layout-wrapper">
    <?php include '../../templates/sidebar_apoteker.php'; ?>

    <main class="main-content">
        <div class="px-4 py-4">
            <h5 class="fw-bold mb-0">Data Penerimaan Obat</h5>
            <?php include '../../modules/penerimaan/partials/table_apoteker.php'; ?>
        </div>
    </main>
</div>

<script src="<?= BASE_URL ?>js/active_menu.js"></script>
</body>

</html>