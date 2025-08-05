<?php
include '../../config/config.php';
include_once __DIR__ . '/../../../config/db.php';

$latestQuery = "
    SELECT p.id_penjualan, p.tgl_penjualan, o.nama_obat, d.jml_terjual
    FROM penjualan_obat p
    JOIN detail_penjualan d ON p.id_penjualan = d.id_penjualan
    JOIN obat o ON d.kode_obat = o.kode_obat
    ORDER BY p.tgl_penjualan DESC, p.id_penjualan DESC
    LIMIT 7
";
$penjualanTerakhir = mysqli_query($conn, $latestQuery);
