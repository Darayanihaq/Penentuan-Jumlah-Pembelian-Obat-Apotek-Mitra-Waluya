<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../../config/auth.php';
onlyPengadaan();

$id_penerimaan = generatePenerimaanId($conn);
$tgl_penerimaan = $_POST['tgl_penerimaan'];
$no_batch = $_POST['no_batch'];
$jml_masuk = $_POST['jml_masuk'];
$tgl_kedaluwarsa = $_POST['tgl_kedaluwarsa'];
$kode_obat = $_POST['kode_obat'];
$id_supplier = $_POST['id_supplier'];

// Validasi apakah obat dan supplier ada
if (!validasiObatDanSupplier($conn, $kode_obat, $id_supplier)) {
    $_SESSION['alert'] = [
        'type' => 'danger',
        'message' => 'Obat atau supplier tidak ditemukan di database.'
    ];
    header("Location: " . $BASE_URL . "pages/pengadaan/penerimaan.php");
    exit();
}

// Insert ke tabel penerimaan_obat
$insert = mysqli_query($conn, "INSERT INTO penerimaan_obat 
    (id_penerimaan, tgl_penerimaan, no_batch, jml_masuk, tgl_kedaluwarsa, id_supplier, kode_obat)
    VALUES 
    ('$id_penerimaan', '$tgl_penerimaan', '$no_batch', '$jml_masuk', '$tgl_kedaluwarsa', '$id_supplier', '$kode_obat')");

if ($insert) {
    tambahStok($conn, $id_penerimaan, $kode_obat, $jml_masuk);

    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'Data penerimaan berhasil ditambahkan.'
    ];
} else {
    $_SESSION['alert'] = [
        'type' => 'danger',
        'message' => 'Gagal menyimpan data ke database. Error: ' . mysqli_error($conn)
    ];
}


header("Location: ../../pages/pengadaan/penerimaan.php");
exit;
