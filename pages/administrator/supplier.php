<?php
session_start();
include '../../config/db.php';
include '../../config/config.php';
include '../../templates/header.php';
include '../../modules/supplier/functions.php';
include '../../config/auth.php';
include '../../components/alert.php';

onlyAdmin();

$alert = $_SESSION['alert'] ?? null;
unset($_SESSION['alert']);

$isEdit = false;
$dataEdit = null;

if (isset($_GET['edit'])) {
    $dataEdit = getSupplierById($conn, $_GET['edit']);
    $isEdit = $dataEdit !== null;
}
?>

<div class="layout-wrapper">
    <?php include '../../templates/sidebar_administrator.php'; ?>

    <main class="main-content">
        <div class="px-4 py-4">
            <h2 class="h5 pb-2">Data Supplier</h2>
            <?php include '../../components/alert.php'; ?>
            <?php include '../../modules/supplier/partials/form.php'; ?>
            <?php include '../../modules/supplier/partials/table.php'; ?>
        </div>
    </main>

</div>

<script src="<?= BASE_URL ?>js/active_menu.js"></script>
<script src="<?= BASE_URL ?>js/searchtable.js"></script>
</body>

</html>