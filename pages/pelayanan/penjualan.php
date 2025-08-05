<?php
session_start();
require_once '../../config/db.php';
require_once '../../config/config.php';
include '../../templates/header.php';
include '../../modules/penjualan/functions.php';
require_once '../../config/auth.php';

onlyPelayanan();

$isEdit = false;
$dataEdit = [];
$kode_obat = '';

if (isset($_GET['edit'])) {
    $isEdit = true;
    $id = $_GET['edit'];
    $dataEdit = getDataPenjualanById($conn, $id);

    if ($dataEdit) {
        $kode_obat = $dataEdit['kode_obat'];
    } else {
        $isEdit = false;
        $dataEdit = [];
        $kode_obat = '';
    }
}
?>

<div class="layout-wrapper">
    <?php include '../../templates/sidebar_pelayanan.php'; ?>
    <main class="main-content">
        <div class="px-4 py-4">
            <?php include '../../components/alert.php'; ?>
            <?php include '../../modules/penjualan/partials/form.php'; ?>

            <?php include '../../modules/penjualan/partials/table.php'; ?>
        </div>
    </main>
</div>

<script src="<?= BASE_URL ?>js/active_menu.js"></script>
<script src="/MitraWaluya/js/select2.js"></script>
</body>

</html>