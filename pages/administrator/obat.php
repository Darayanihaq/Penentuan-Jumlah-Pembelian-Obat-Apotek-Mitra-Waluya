<?php
session_start();
include '../../config/db.php';
include '../../config/config.php';
include '../../templates/header.php'; // <html><head> dan header tetap
include '../../modules/obat/functions.php';
include '../../config/auth.php';
include '../../components/alert.php';

onlyAdmin();

$alert = $_SESSION['alert'] ?? null;
unset($_SESSION['alert']);

$isEdit = false;
$dataEdit = null;

if (isset($_GET['edit'])) {
    $kodeEdit = $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM obat WHERE kode_obat = '$kodeEdit'");
    if (mysqli_num_rows($result) > 0) {
        $isEdit = true;
        $dataEdit = mysqli_fetch_assoc($result);
    }
}
?>

<!-- ====== Layout Wrapper Mulai ====== -->
<div class="layout-wrapper">

    <!-- Sidebar tetap -->
    <?php include '../../templates/sidebar_administrator.php'; ?>

    <!-- Main Content (scrollable) -->
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
</body>

</html>