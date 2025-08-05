<?php
session_start();
require_once '../../config/db.php';
require_once '../../config/config.php';
require_once '../../config/auth.php';
include '../../templates/header.php';
include '../../modules/penerimaan/functions.php';
include '../../components/alert.php';
onlyPengadaan();

$alert = $_SESSION['alert'] ?? null;
unset($_SESSION['alert']);

$isEdit = false;
$dataEdit = null;
$kode_obat = '';
$id_supplier = '';

if (isset($_GET['edit'])) {
    $id_penerimaan = $_GET['edit'];
    $dataEdit = getDataEditPenerimaan($conn, $id_penerimaan);

    if ($dataEdit) {
        $isEdit = true;
        $kode_obat = $dataEdit['kode_obat'];
        $id_supplier = $dataEdit['id_supplier'];
    }
}

?>

<div class="layout-wrapper">
    <?php include '../../templates/sidebar_pengadaan.php'; ?>

    <main class="main-content">
        <div class="px-4 py-4">
            <?php include '../../components/alert.php'; ?>
            <?php include '../../modules/penerimaan/partials/form.php'; ?>
            <?php include '../../modules/penerimaan/partials/table.php'; ?>
        </div>
    </main>

</div>

<script src="<?= BASE_URL ?>js/active_menu.js"></script>
<script src="<?= BASE_URL ?>js/select2.js"></script>
</body>

</html>