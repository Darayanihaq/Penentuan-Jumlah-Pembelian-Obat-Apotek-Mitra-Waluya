<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../../config/auth.php';
require_once __DIR__ . '/../../../config/config.php';
onlyPengadaan();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'tambah_pembelian.php';
    exit;
}

// Kalau hanya loader data, jangan ada proses simpan

header("Location: " . BASE_URL . "pages/pengadaan/pembelian.php");
exit;
