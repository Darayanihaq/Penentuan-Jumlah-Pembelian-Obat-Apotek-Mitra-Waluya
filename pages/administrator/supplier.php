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
    $kodeEdit = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM supplier WHERE id_supplier = '$kodeEdit'");
    if (mysqli_num_rows($result) > 0) {
        $isEdit = true;
        $dataEdit = mysqli_fetch_assoc($result);
    }
}
?>

<!-- ===== Layout wrapper dimulai ===== -->
<div class="layout-wrapper">

    <!-- Sidebar tetap di kiri -->
    <?php include '../../templates/sidebar_administrator.php'; ?>

    <!-- Main Content (scrollable) -->
    <main class="main-content">
        <div class="px-4 py-4">
            <h2 class="h5 pb-2">Data Supplier</h2>

            <?php if ($alert): ?>
                <?php include '../../components/alert.php'; ?>
            <?php endif; ?>

            <?php include '../../modules/supplier/partials/form.php'; ?>
            <?php include '../../modules/supplier/partials/table.php'; ?>
        </div>
    </main>

</div>

<script src="<?= BASE_URL ?>js/active_menu.js"></script>
<script src="<?= BASE_URL ?>js/searchtable.js"></script>
</body>

</html>