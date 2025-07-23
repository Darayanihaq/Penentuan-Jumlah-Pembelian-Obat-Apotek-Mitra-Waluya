<?php
include '../../config/db.php';

$query = "
    SELECT 
        p.id_penerimaan,
        p.no_batch, 
        o.nama_obat,
        o.jenis,
        MAX(p.tgl_kedaluwarsa) AS tgl_kedaluwarsa, 
        MIN(p.tgl_penerimaan) AS tgl_penerimaan,
        s.jml_stok AS jml_stok_saat_ini
    FROM 
        penerimaan_obat p
    INNER JOIN 
        stok_obat s ON p.id_penerimaan = s.id_penerimaan AND s.kode_obat = p.kode_obat
    INNER JOIN 
        obat o ON s.kode_obat = o.kode_obat
    GROUP BY 
        p.id_penerimaan, p.no_batch, o.nama_obat, o.jenis, p.tgl_kedaluwarsa, p.tgl_penerimaan, s.jml_stok
    ORDER BY 
        p.tgl_kedaluwarsa ASC
";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query Gagal: " . mysqli_error($conn));
}
