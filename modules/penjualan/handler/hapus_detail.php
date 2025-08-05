<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../../config/db.php';
require_once '../functions.php';

if (!isset($_GET['id_detail'])) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'ID detail tidak ditemukan.'];
    header("Location: ../../../pages/pelayanan/penjualan.php");
    exit;
}

$id_detail = $_GET['id_detail'];

// Ambil id_penjualan sebelum hapus
$getPenjualan = mysqli_query($conn, "SELECT id_penjualan FROM detail_penjualan WHERE id_detail = '$id_detail'");
$row = mysqli_fetch_assoc($getPenjualan);
$id_penjualan = $row['id_penjualan'];

mysqli_begin_transaction($conn);
try {
    hapusDetailPenjualan($conn, $id_detail); // Ini juga mengembalikan stok
    mysqli_commit($conn);
    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Detail penjualan berhasil dihapus.'];
} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal menghapus detail: ' . $e->getMessage()];
    header("Location: ../../../pages/pelayanan/penjualan.php");
    exit;
}

// Setelah hapus detail, cek apakah masih ada sisa detail
$check = mysqli_query($conn, "SELECT COUNT(*) AS total FROM detail_penjualan WHERE id_penjualan = '$id_penjualan'");
$count = mysqli_fetch_assoc($check)['total'];

if ($count == 0) {
    mysqli_query($conn, "DELETE FROM penjualan_obat WHERE id_penjualan = '$id_penjualan'");
}

header("Location: ../../../pages/pelayanan/penjualan.php");
exit;
