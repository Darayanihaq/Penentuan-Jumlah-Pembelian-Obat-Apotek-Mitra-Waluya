<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/config.php';
onlyPengadaan();

if (isset($_POST['tambah'])) {
    include 'handler/tambah_penerimaan.php';
} elseif (isset($_POST['update'])) {
    include 'handler/ubah_penerimaan.php';
} elseif (isset($_GET['delete'])) {
    include 'handler/hapus_penerimaan.php';
    exit();
} else {
    header("Location: " . BASE_URL . "pages/pengadaan/penerimaan.php");
    exit;
}
?>