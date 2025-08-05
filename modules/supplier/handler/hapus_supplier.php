<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../../config/auth.php';
require_once __DIR__ . '/../../../config/config.php';
onlyAdmin();

if (isset($_GET['id_supplier'])) {
    $id_supplier = $_GET['id_supplier'];
    $hapus = mysqli_query($conn, "DELETE FROM supplier WHERE id_supplier = '$id_supplier'");

    if ($hapus) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Data supplier berhasil dihapus.'];
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal menghapus obat. Error: ' . mysqli_error($conn)];
    }
}

header("Location: " . BASE_URL . "pages/administrator/supplier.php");
exit;