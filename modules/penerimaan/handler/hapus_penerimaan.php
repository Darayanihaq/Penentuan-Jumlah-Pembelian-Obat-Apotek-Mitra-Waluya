<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../../config/auth.php';
onlyPengadaan();

$id = $_GET['delete'];
$data = ambilPenerimaan($conn, $id);

$cek = mysqli_query($conn, "
    SELECT COUNT(*) as jml FROM detail_penjualan dp
    JOIN stok_obat s ON dp.id_stok = s.id_stok
    WHERE s.id_penerimaan = '$id'
");
$hasil = mysqli_fetch_assoc($cek);

if ($hasil['jml'] > 0) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Tidak dapat menghapus: Stok sudah digunakan dalam transaksi penjualan.'];
    header("Location: ../../../pages/pengadaan/penerimaan.php");
    exit;
}

hapusStok($conn, $id);

mysqli_query($conn, "DELETE FROM penerimaan_obat WHERE id_penerimaan='$id'");

$_SESSION['alert'] = ['type' => 'success', 'message' => 'Data penerimaan berhasil dihapus.'];
header("Location: ../../../pages/pengadaan/penerimaan.php");
exit();
