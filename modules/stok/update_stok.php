<?php
require_once '../../config/db.php';
require_once '../../config/config.php';

date_default_timezone_set('Asia/Jakarta');

// Ambil semua stok dengan data penerimaan (tgl_kedaluwarsa)
$query = mysqli_query($conn, "
    SELECT s.id_stok, s.jml_stok, p.tgl_kedaluwarsa
    FROM stok_obat s
    JOIN penerimaan_obat p ON s.id_penerimaan = p.id_penerimaan
");

$today = strtotime(date('Y-m-d'));
$updated = 0;

while ($row = mysqli_fetch_assoc($query)) {
    $id_stok = $row['id_stok'];
    $stok = (int) $row['jml_stok'];
    $tgl_kedaluwarsa = strtotime($row['tgl_kedaluwarsa']);
    $selisih_hari = ($tgl_kedaluwarsa - $today) / (60 * 60 * 24);

    if ($stok <= 0) {
        $status = 'Stok Habis';
    } elseif ($stok <= 10) {
        $status = 'Hampir Habis';
    } elseif ($selisih_hari <= 30) {
        $status = 'Kedaluwarsa';
    } else {
        $status = 'Tersedia';
    }

    // Update status hanya jika berbeda
    mysqli_query($conn, "
        UPDATE stok_obat 
        SET status_stok = '$status' 
        WHERE id_stok = '$id_stok'
    ");

    $updated++;
}

echo "Selesai memperbarui status $updated stok.";
?>