<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../../config/auth.php';

onlyAdmin();

$nama_obat = mysqli_real_escape_string($conn, $_POST['nama_obat']);
$satuan = mysqli_real_escape_string($conn, $_POST['satuan']);
$jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
$harga_obat = mysqli_real_escape_string($conn, $_POST['harga_obat']);


$cek = mysqli_query($conn, "SELECT * FROM obat WHERE nama_obat = '$nama_obat' AND jenis = '$jenis'");
if (mysqli_num_rows($cek) > 0) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Nama obat dengan jenis tersebut sudah ada dalam database!'];
    header("Location: " . BASE_URL . "pages/administrator/obat.php");
    exit;
}


$kode_obat = generateKodeObat($conn, $jenis);
$insert = mysqli_query($conn, "INSERT INTO obat (kode_obat, nama_obat, satuan, jenis, harga_obat)
                               VALUES ('$kode_obat', '$nama_obat', '$satuan', '$jenis', '$harga_obat')");

$_SESSION['alert'] = $insert
    ? ['type' => 'success', 'message' => 'Data berhasil ditambahkan.']
    : ['type' => 'danger', 'message' => 'Gagal menambahkan data.'];

header("Location: " . BASE_URL . "pages/administrator/obat.php");
exit;
