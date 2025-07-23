<?php
session_start();
require_once '../../../config/db.php';
require_once '../functions.php';

if (!isset($_GET['id'])) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'ID penjualan tidak ditemukan.'];
    header("Location: ../../../pages/pelayanan/penjualan.php");
    exit;
}

$id_penjualan = $_GET['id'];

mysqli_begin_transaction($conn);

try {
    // Ambil semua detail untuk rollback stok
    $details = mysqli_query($conn, "SELECT id_stok, jml_terjual FROM detail_penjualan WHERE id_penjualan = '$id_penjualan'");
    if (!$details) {
        throw new Exception("Gagal mengambil detail penjualan.");
    }

    while ($detail = mysqli_fetch_assoc($details)) {
        if (!tambahStokKembali($conn, $detail['id_stok'], $detail['jml_terjual'])) {
            throw new Exception("Gagal mengembalikan stok untuk ID Stok: " . $detail['id_stok']);
        }
    }

    // Hapus detail penjualan
    if (!mysqli_query($conn, "DELETE FROM detail_penjualan WHERE id_penjualan = '$id_penjualan'")) {
        throw new Exception("Gagal menghapus detail penjualan.");
    }

    // Hapus transaksi utama
    if (!mysqli_query($conn, "DELETE FROM penjualan_obat WHERE id_penjualan = '$id_penjualan'")) {
        throw new Exception("Gagal menghapus data penjualan.");
    }

    mysqli_commit($conn);
    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Transaksi penjualan berhasil dihapus.'];

} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal menghapus transaksi: ' . $e->getMessage()];
}

header("Location: ../../../pages/pelayanan/penjualan.php");
exit;