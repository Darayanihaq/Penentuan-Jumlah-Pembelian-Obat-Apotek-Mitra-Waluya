<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Penanganan aksi form berdasarkan tombol yang ditekan
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
