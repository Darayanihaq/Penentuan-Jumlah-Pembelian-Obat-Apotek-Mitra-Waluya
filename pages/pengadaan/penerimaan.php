<?php
session_start();
include '../../config/db.php';
include '../../config/config.php';
include '../../templates/header.php'; // <html><head> dan header tetap
include '../../modules/penerimaan/functions.php';
include '../../config/auth.php';
include '../../components/alert.php';
onlyPengadaan();


$alert = $_SESSION['alert'] ?? null;
unset($_SESSION['alert']);

$isEdit = false;
$dataEdit = null;

if (isset($_GET['edit'])) {
    $id_penerimaan = $_GET['edit']; // Gunakan ini untuk query
    $result = mysqli_query($conn, "SELECT * FROM penerimaan_obat WHERE id_penerimaan = '$id_penerimaan'");
    if (mysqli_num_rows($result) > 0) {
        $isEdit = true;
        $dataEdit = mysqli_fetch_assoc($result);

        // Siapkan juga variabel ini agar dipakai di <select>
        $kode_obat = $dataEdit['kode_obat'];
        $id_supplier = $dataEdit['id_supplier'];
    }
}
?>

<!-- ====== Layout Wrapper Mulai ====== -->
<div class="layout-wrapper">

    <!-- Sidebar tetap -->
    <?php include '../../templates/sidebar_pengadaan.php'; ?>

    <!-- Main Content (scrollable) -->
    <main class="main-content">
        <div class="px-4 py-4">
            <?php include '../../components/alert.php'; ?>
            <?php include '../../modules/penerimaan/partials/form.php'; ?>
            <?php
            // Awal query
            $query = "
                SELECT p.*, o.nama_obat, o.jenis, s.nama_supplier
                FROM penerimaan_obat p
                JOIN obat o ON p.kode_obat = o.kode_obat
                JOIN supplier s ON p.id_supplier = s.id_supplier
                WHERE 1
            ";

            // Filter bulan jika ada input
            if (!empty($_GET['bulan'])) {
                $bulan = (int) $_GET['bulan'];
                $query .= " AND MONTH(p.tgl_penerimaan) = $bulan";
            }

            // Filter tahun jika ada input
            if (!empty($_GET['tahun'])) {
                $tahun = (int) $_GET['tahun'];
                $query .= " AND YEAR(p.tgl_penerimaan) = $tahun";
            }

            // Urutkan
            $query .= " ORDER BY p.tgl_penerimaan DESC";

            // Eksekusi query
            try {
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                $result = mysqli_query($conn, $query);
            } catch (Exception $e) {
                die("Query error: " . $e->getMessage());
            }
            ?>
            <?php include '../../modules/penerimaan/partials/table.php'; ?>
        </div>
    </main>

</div>

<script src="<?= BASE_URL ?>js/active_menu.js"></script>
<script src="<?= BASE_URL ?>js/select2.js"></script>
</body>

</html>