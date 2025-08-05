<?php
include_once __DIR__ . '/../../../config/db.php';

$penerimaan = mysqli_query($conn, "
    SELECT p.id_penerimaan, p.tgl_penerimaan, o.nama_obat, p.jml_masuk, s.nama_supplier
    FROM penerimaan_obat p
    JOIN obat o ON p.kode_obat = o.kode_obat
    JOIN supplier s ON p.id_supplier = s.id_supplier
    ORDER BY p.tgl_penerimaan DESC
    LIMIT 7
") or die('Query Penerimaan Error: ' . mysqli_error($conn));
?>