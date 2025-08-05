<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../../config/auth.php';

onlyAdmin();

$nama_user = mysqli_real_escape_string($conn, $_POST['nama_user']);
$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];


$cek = mysqli_query($conn, "SELECT * FROM pengguna WHERE username = '$username'");
if (mysqli_num_rows($cek) > 0) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Username sudah ada dalam database!'];
    header("Location: " . BASE_URL . "pages/administrator/pengguna.php");
    exit;
}

$id_user = generateUserId($conn);
$insert = mysqli_query($conn, "INSERT INTO pengguna (id_user, nama_user, username, password, role) 
           VALUES ('$id_user', '$nama_user', '$username', '$password', '$role')");

$_SESSION['alert'] = $insert
    ? ['type' => 'success', 'message' => 'Data berhasil ditambahkan.']
    : ['type' => 'danger', 'message' => 'Gagal menambahkan data.'];


header("Location: " . BASE_URL . "pages/administrator/pengguna.php");
exit;