<?php
// filepath: c:\xampp\htdocs\MitraWaluya\modules\obat\handler\hapus_obat.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../../config/auth.php';
require_once __DIR__ . '/../../../config/config.php';
onlyAdmin();

if (isset($_GET['kode_obat'])) {
    $kode_obat = $_GET['kode_obat'];
    $hapus = mysqli_query($conn, "DELETE FROM obat WHERE kode_obat = '$kode_obat'");

    if ($hapus) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Obat berhasil dihapus.'];
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal menghapus obat. Error: ' . mysqli_error($conn)];
    }
}

header("Location: " . BASE_URL . "pages/administrator/obat.php");
exit;