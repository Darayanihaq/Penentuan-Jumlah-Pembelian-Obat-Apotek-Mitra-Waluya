<?php
session_start();
require_once '../../config/db.php';
require_once '../../config/config.php';
include '../../templates/header.php';
require_once '../../config/auth.php';
onlyPengadaan();
?>

<!-- Wrapper Layout -->
<div class="layout-wrapper">

    <!-- Sidebar -->
    <?php include '../../templates/sidebar_pengadaan.php'; ?>
    <!-- Main Content -->
    <main class="main-content">
        <div class="px-4 py-4">
            <?php include '../../components/alert.php'; ?>
            <?php include '../../modules/pembelian/partials/form.php'; ?>
            <?php
            include '../../config/db.php';

            $query = "
                SELECT 
                    p.id_pembelian, p.kode_obat, p.id_supplier, p.jml_pembelian,
                    o.nama_obat, o.jenis, o.harga_obat,
                    s.nama_supplier,
                    r.bulan_peramalan, r.hasil_peramalan, r.mad_peramalan, r.mape_peramalan, r.id_peramalan,
                    p.jml_pembelian * o.harga_obat AS total_pembelian
                FROM pembelian p
                JOIN obat o ON p.kode_obat = o.kode_obat
                JOIN supplier s ON p.id_supplier = s.id_supplier
                LEFT JOIN peramalan r ON p.id_peramalan = r.id_peramalan
                ORDER BY p.id_pembelian DESC
            ";
            $result = mysqli_query($conn, $query);

            if (!$result) {
                echo "<pre>Error SQL: " . mysqli_error($conn) . "</pre>";
            } ?>
            <?php include '../../modules/pembelian/partials/table.php'; ?>
            <?php include '../../modules/pembelian/partials/modal_detail.php'; ?>

        </div>
    </main>

</div>
<script src="<?= BASE_URL ?>js/active_menu.js"></script>
<script src="<?= BASE_URL ?>js/select2.js"></script>
</body>

</html>