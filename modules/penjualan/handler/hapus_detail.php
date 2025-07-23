<?php
session_start();
require_once '../../../config/db.php';
require_once '../functions.php';

if (!isset($_GET['id_detail'])) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'ID detail tidak ditemukan.'];
    header("Location: ../../../pages/pelayanan/penjualan.php");
    exit;
}

$id_detail = $_GET['id_detail'];

mysqli_begin_transaction($conn);
try {
    hapusDetailPenjualan($conn, $id_detail); // fungsi yang sudah kita bahas
    mysqli_commit($conn);
    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Detail penjualan berhasil dihapus.'];
} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal menghapus detail: ' . $e->getMessage()];
}

// Ambil id_penjualan dari detail
$getPenjualan = mysqli_query($conn, "SELECT id_penjualan FROM detail_penjualan WHERE id_detail = '$id_detail'");
$row = mysqli_fetch_assoc($getPenjualan);
$id_penjualan = $row['id_penjualan'];

// Cek apakah masih ada detail lain
$check = mysqli_query($conn, "SELECT COUNT(*) AS total FROM detail_penjualan WHERE id_penjualan = '$id_penjualan'");
$count = mysqli_fetch_assoc($check)['total'];

if ($count == 0) {
    mysqli_query($conn, "DELETE FROM penjualan_obat WHERE id_penjualan = '$id_penjualan'");
}

header("Location: ../../../pages/pelayanan/penjualan.php");
exit;
