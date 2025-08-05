<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/config.php';

onlyAdmin();

if (isset($_POST['tambah'])) {
    include 'handler/tambah_pengguna.php';
} elseif (isset($_POST['update'])) {
    include 'handler/update_pengguna.php';
} else {
    header("Location: " . BASE_URL . "pages/administrator/pengguna.php");
    exit;
}
