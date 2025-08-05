<?php
session_start();
include '../../config/db.php';
include '../../config/config.php';
include '../../templates/header.php';
include '../../modules/obat/functions.php';
include '../../config/auth.php';
include '../../components/alert.php';

onlyAdmin();

$alert = $_SESSION['alert'] ?? null;
unset($_SESSION['alert']);

$isEdit = isset($_GET['edit']);
$dataEdit = $isEdit ? getDataObatByKode($conn, $_GET['edit']) : null;
$isEdit = $dataEdit !== null;

$jenisObatList = mysqli_query($conn, "SELECT DISTINCT jenis FROM obat ORDER BY jenis ASC");
?>

<div class="layout-wrapper">
    <?php include '../../templates/sidebar_administrator.php'; ?>

    <main class="main-content">
        <div class="px-4 py-4">
            <h2 class="h5 pb-2">Data Obat</h2>
            <?php include '../../components/alert.php'; ?>
            <?php include '../../modules/obat/partials/form.php'; ?>
            <?php include '../../modules/obat/partials/table.php'; ?>
        </div>
    </main>

</div>

<script src="<?= BASE_URL ?>js/active_menu.js"></script>
<script src="<?= BASE_URL ?>js/searchtable.js"></script>
<script src="<?= BASE_URL ?>js/obat_table_filter.js"></script>

</body>

</html>