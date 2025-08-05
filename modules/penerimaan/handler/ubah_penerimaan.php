<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../../config/auth.php';
onlyPengadaan();

$id_penerimaan = $_POST['id_penerimaan'];
$tgl_penerimaan = $_POST['tgl_penerimaan'];
$no_batch = $_POST['no_batch'];
$jml_masuk = $_POST['jml_masuk'];
$tgl_kedaluwarsa = $_POST['tgl_kedaluwarsa'];
$kode_obat = $_POST['kode_obat'];
$id_supplier = $_POST['id_supplier'];

mysqli_begin_transaction($conn);

try {
    if (!validasiObatDanSupplier($conn, $kode_obat, $id_supplier)) {
        throw new Exception('Obat atau supplier tidak ditemukan dalam database.');
    }

    $data_lama = ambilPenerimaan($conn, $id_penerimaan);
    if (!$data_lama) {
        throw new Exception('Data penerimaan tidak ditemukan.');
    }

    $update = mysqli_query($conn, "UPDATE penerimaan_obat SET 
        tgl_penerimaan = '$tgl_penerimaan',
        no_batch = '$no_batch',
        jml_masuk = '$jml_masuk',
        tgl_kedaluwarsa = '$tgl_kedaluwarsa',
        id_supplier = '$id_supplier',
        kode_obat = '$kode_obat'
        WHERE id_penerimaan = '$id_penerimaan'");

    if (!$update) {
        throw new Exception('Gagal memperbarui data penerimaan: ' . mysqli_error($conn));
    }

    if (!updateStokSetelahEdit($conn, $id_penerimaan, $jml_masuk, $data_lama['jml_masuk'])) {
        throw new Exception('Gagal memperbarui stok.');
    }

    mysqli_commit($conn);
    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'Data penerimaan berhasil diperbarui.'
    ];

} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['alert'] = [
        'type' => 'danger',
        'message' => $e->getMessage()
    ];
}

header("Location: " . BASE_URL . "pages/pengadaan/penerimaan.php");
exit;