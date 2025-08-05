<?php
session_start();
require_once '../../config/db.php';
require_once '../../config/config.php';
include '../../templates/header.php';
require_once '../../config/auth.php';

onlyApoteker();
?>

<div class="layout-wrapper">
    <?php include '../../templates/sidebar_apoteker.php'; ?>

    <main class="main-content">
        <div class="px-4 py-3">
            <h5 class="fw-bold mb-2">Data Pembelian Obat</h5>
            <?php
            include '../../config/db.php';

            $query = "
                SELECT 
                    p.id_pembelian, p.bulan_pembelian, p.tahun_pembelian, p.kode_obat, p.id_supplier, p.jml_pembelian, 
                    o.nama_obat, o.jenis, o.harga_obat,
                    s.nama_supplier,
                    r.bulan_peramalan, r.hasil_peramalan, r.mad_peramalan, r.mape_peramalan, r.id_peramalan,
                    p.jml_pembelian * o.harga_obat AS total_pembelian
                FROM pembelian p
                JOIN obat o ON p.kode_obat = o.kode_obat
                JOIN supplier s ON p.id_supplier = s.id_supplier
                LEFT JOIN peramalan r ON p.id_peramalan = r.id_peramalan
                ORDER BY 
                p.tahun_pembelian DESC,
                FIELD(p.bulan_pembelian, 
                    'Januari','Februari','Maret','April','Mei','Juni',
                    'Juli','Agustus','September','Oktober','November','Desember'
                ) DESC
            ";
            $result = mysqli_query($conn, $query);
            if (!$result) {
                die("Query error: " . mysqli_error($conn));
            }
            ?>
            <?php include '../../modules/pembelian/partials/table_apoteker.php'; ?>
            <?php include '../../modules/pembelian/partials/modal_detail.php'; ?>
        </div>
    </main>
</div>

<script src="<?= BASE_URL ?>js/active_menu.js"></script>
</body>

</html>