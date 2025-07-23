<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include koneksi dan fungsi otentikasi
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/config.php';
onlyAdmin();

if (isset($_POST['tambah'])) {
    include 'handler/tambah_supplier.php';
} elseif (isset($_POST['update'])) {
    include 'handler/update_supplier.php';
} else {
    // Kalau tidak valid
    header("Location: " . BASE_URL . "pages/administrator/supplier.php");
    exit;
}
