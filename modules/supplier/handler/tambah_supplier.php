<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../../config/auth.php';

onlyAdmin();

$nama_supplier = mysqli_real_escape_string($conn, $_POST['nama_supplier']);
$alamat = $_POST['alamat'];
$no_kontak = $_POST['no_kontak'];

// Cek duplikat supplier
$cek = mysqli_query($conn, "SELECT * FROM supplier WHERE nama_supplier = '$nama_supplier'");
if (mysqli_num_rows($cek) > 0) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Supplier sudah ada dalam database!'];
    header("Location: " . BASE_URL . "pages/administrator/supplier.php");
    exit;
}

$id_supplier = generateSupplierId($conn);

$insert = mysqli_query($conn, "INSERT INTO supplier (id_supplier, nama_supplier, alamat, no_kontak) 
          VALUES ('$id_supplier', '$nama_supplier', '$alamat', '$no_kontak')");

$_SESSION['alert'] = $insert
    ? ['type' => 'success', 'message' => 'Data berhasil ditambahkan.']
    : ['type' => 'danger', 'message' => 'Gagal menambahkan data.'];


header("Location: " . BASE_URL . "pages/administrator/supplier.php");
exit;