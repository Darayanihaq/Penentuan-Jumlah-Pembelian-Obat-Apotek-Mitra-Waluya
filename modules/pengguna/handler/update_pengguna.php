<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../config/config.php';

onlyAdmin();


$id_user = $_POST['id_user'];
$nama_user = mysqli_real_escape_string($conn, $_POST['nama_user']);
$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];

$update = mysqli_query($conn, "UPDATE pengguna SET 
    nama_user='$nama_user',
    username='$username',
    password='$password',
    role='$role'
    WHERE id_user='$id_user'");

$_SESSION['alert'] = $update
    ? ['type' => 'success', 'message' => 'Data berhasil diperbarui.']
    : ['type' => 'danger', 'message' => 'Gagal memperbarui data.'];

header("Location: " . BASE_URL . "pages/administrator/pengguna.php");
exit;