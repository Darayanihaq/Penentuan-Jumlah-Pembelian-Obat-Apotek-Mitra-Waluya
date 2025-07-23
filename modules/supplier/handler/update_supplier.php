<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../config/config.php';
onlyAdmin();

$id_supplier = $_POST['id_supplier'];
$nama_supplier = mysqli_real_escape_string($conn, $_POST['nama_supplier']);
$alamat = $_POST['alamat'];
$no_kontak = $_POST['no_kontak'];

$update = mysqli_query($conn, "UPDATE supplier SET 
    nama_supplier='$nama_supplier',
    alamat='$alamat',
    no_kontak='$no_kontak' 
    WHERE id_supplier='$id_supplier'");

$_SESSION['alert'] = $update
    ? ['type' => 'success', 'message' => 'Data berhasil diperbarui.']
    : ['type' => 'danger', 'message' => 'Gagal memperbarui data.'];

header("Location: " . BASE_URL . "pages/administrator/supplier.php");
exit;