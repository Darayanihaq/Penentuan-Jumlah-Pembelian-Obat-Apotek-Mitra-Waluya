<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../../config/auth.php';
require_once __DIR__ . '/../../../config/config.php';
onlyPengadaan();

if (!isset($_GET['id_pembelian']) || !isset($_GET['id_peramalan'])) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Data pembelian atau peramalan tidak ditemukan.'];
    header("Location: " . BASE_URL . "pages/pengadaan/pembelian.php");
    exit;
}

$id_pembelian = $_GET['id_pembelian'];
$id_peramalan = $_GET['id_peramalan'];

try {
    mysqli_begin_transaction($conn);

    mysqli_query($conn, "DELETE FROM pembelian WHERE id_pembelian = '$id_pembelian'");
    mysqli_query($conn, "DELETE FROM peramalan WHERE id_peramalan = '$id_peramalan'");

    mysqli_commit($conn);

    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Data pembelian berhasil dihapus.'];
} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal menghapus pembelian: ' . $e->getMessage()];
}

header("Location: " . BASE_URL . "pages/pengadaan/pembelian.php");
exit;
