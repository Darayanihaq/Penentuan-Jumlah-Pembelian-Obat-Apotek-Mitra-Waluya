<?php
session_start();
require_once '../../config/db.php';
require_once '../../config/config.php';
include '../../templates/header.php';
require_once '../../config/auth.php';
onlyPelayanan();

$isEdit = false;
$dataEdit = [];

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $isEdit = true;
    $editQuery = mysqli_query($conn, "SELECT p.*, d.jml_terjual, d.kode_obat 
        FROM penjualan_obat p 
        JOIN detail_penjualan d ON p.id_penjualan = d.id_penjualan
        WHERE p.id_penjualan = '$id' LIMIT 1");
    $dataEdit = mysqli_fetch_assoc($editQuery);
    $kode_obat = $dataEdit['kode_obat']; // untuk select dropdown
}

?>

<div class="layout-wrapper">
    <?php include '../../templates/sidebar_pelayanan.php'; ?>
    <main class="main-content">
        <div class="px-4 py-4">
            <?php include '../../components/alert.php'; ?>
            <?php include '../../modules/penjualan/partials/form.php'; ?>
            <?php
            $query = "
                SELECT 
                    p.id_penjualan, 
                    p.tgl_penjualan, 
                    d.id_detail, 
                    d.jml_terjual, 
                    o.nama_obat, 
                    o.jenis,
                    r.no_batch
                FROM penjualan_obat p
                JOIN detail_penjualan d ON p.id_penjualan = d.id_penjualan
                JOIN obat o ON d.kode_obat = o.kode_obat
                JOIN stok_obat s ON d.id_stok = s.id_stok
                JOIN penerimaan_obat r ON s.id_penerimaan = r.id_penerimaan
                WHERE 1
            ";

            if (!empty($_GET['bulan'])) {
                $bulan = (int) $_GET['bulan'];
                $query .= " AND MONTH(p.tgl_penjualan) = $bulan";
            }

            if (!empty($_GET['tahun'])) {
                $tahun = (int) $_GET['tahun'];
                $query .= " AND YEAR(p.tgl_penjualan) = $tahun";
            }

            $query .= " ORDER BY p.tgl_penjualan DESC";

            try {
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                $result = mysqli_query($conn, $query);
            } catch (Exception $e) {
                die("Query error: " . $e->getMessage());
            }

            $bulanIndonesia = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            ];
            ?>
            <?php include '../../modules/penjualan/partials/table.php'; ?>
        </div>
    </main>
</div>

<script src="<?= BASE_URL ?>js/active_menu.js"></script>
<script src="/MitraWaluya/js/select2.js"></script>
</body>

</html>