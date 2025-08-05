<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../config/config.php';
onlyAdmin();

$kode_obat = mysqli_real_escape_string($conn, $_POST['kode_obat']);
$nama_obat = mysqli_real_escape_string($conn, $_POST['nama_obat']);
$satuan = $_POST['satuan'];
$jenis = $_POST['jenis'];
$harga_obat = $_POST['harga_obat'];

$update = mysqli_query($conn, "UPDATE obat SET 
    nama_obat = '$nama_obat',
    satuan = '$satuan',
    jenis = '$jenis',
    harga_obat = '$harga_obat'
    WHERE kode_obat = '$kode_obat'");

$_SESSION['alert'] = $update
    ? ['type' => 'success', 'message' => 'Data berhasil diperbarui.']
    : ['type' => 'danger', 'message' => 'Gagal memperbarui data.'];

header("Location: " . BASE_URL . "pages/administrator/obat.php");
exit;
