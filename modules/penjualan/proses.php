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
    include '/handler/tambah_penjualan.php';

} elseif (isset($_POST['update'])) {
    include '/handler/ubah_penjualan.php';

} elseif (isset($_GET['delete'])) {
    include '/handler/hapus_penjualan.php';

} else {
    $_SESSION['alert'] = ['type' => 'warning', 'message' => 'Aksi tidak dikenali.'];
    header('Location: ../../pages/pelayanan/penjualan.php');
    exit;
}
