<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../config/config.php';
onlyAdmin();

$kode_obat = mysqli_real_escape_string($conn, $_POST['kode_obat']);
$nama_obat = mysqli_real_escape_string($conn, $_POST['nama_obat']);
$satuan = mysqli_real_escape_string($conn, $_POST['satuan']);
$jenis_baru = mysqli_real_escape_string($conn, $_POST['jenis']);
$harga_obat = mysqli_real_escape_string($conn, $_POST['harga_obat']);

// Ambil data lama
$dataLama = getDataObatByKode($conn, $kode_obat);

if ($dataLama === null) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Data obat tidak ditemukan!'];
    header("Location: " . BASE_URL . "pages/administrator/obat.php");
    exit;
}

// Cek apakah jenis berubah
if ($dataLama['jenis'] !== $jenis_baru) {
    $kode_obat_baru = generateKodeObat($conn, $jenis_baru);
} else {
    $kode_obat_baru = $kode_obat;
}

// Update data
$update = mysqli_query($conn, "UPDATE obat SET 
    kode_obat = '$kode_obat_baru',
    nama_obat = '$nama_obat',
    satuan = '$satuan',
    jenis = '$jenis_baru',
    harga_obat = '$harga_obat'
    WHERE kode_obat = '$kode_obat'");

$_SESSION['alert'] = $update
    ? ['type' => 'success', 'message' => 'Data berhasil diperbarui.']
    : ['type' => 'danger', 'message' => 'Gagal memperbarui data.'];

header("Location: " . BASE_URL . "pages/administrator/obat.php");
exit;
