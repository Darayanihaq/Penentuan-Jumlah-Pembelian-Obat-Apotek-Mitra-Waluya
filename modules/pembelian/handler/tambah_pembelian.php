<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../../../config/auth.php';
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../peramalan/functions.php';
require_once __DIR__ . '/../../peramalan/logic_wma.php';

onlyPengadaan();

$bulan_peramalan = date('F', strtotime("+1 month")); // contoh: "August"
$tgl_peramalan = date('Y-m-d'); // tanggal hari ini
$id_user = $_SESSION['user']['id_user'];

$kode_obat = $_POST['kode_obat'] ?? '';
$id_supplier = $_POST['id_supplier'] ?? '';

$penjualan = getRiwayatPenjualanObat($conn, $kode_obat);
$riwayat = $penjualan;

if (!is_array($penjualan) || count($penjualan) < 4) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Data penjualan tidak cukup untuk peramalan. Minimal 4 bulan diperlukan.'];
    header("Location: " . BASE_URL . "pages/pengadaan/pembelian.php");
    exit;
}

$forecast_data = hitungWMAArray($penjualan);
$hasil_peramalan = end($forecast_data);

$aktual_mad = array_slice($penjualan, -count($forecast_data));
$mad = hitungMAD($aktual_mad, $forecast_data);

$aktual_mape = array_slice($penjualan, -count($forecast_data));
$mape = hitungMAPE($aktual_mape, $forecast_data);

$jumlah_pembelian = $hasil_peramalan;  // Inisialisasi awal

// Ambil data stok
$stok_akhir = ambilStokAkhir($conn, $kode_obat);
$stok_kedaluwarsa = ambilStokKedaluwarsa($conn, $kode_obat);

// Hitung jumlah pembelian berdasarkan hasil peramalan
if ($stok_akhir == 0 && $stok_kedaluwarsa == 0) {
    $jumlah_pembelian = $hasil_peramalan + 10;
} elseif ($stok_kedaluwarsa > 0) {
    $jumlah_pembelian = ($hasil_peramalan + 10) - ($stok_akhir - $stok_kedaluwarsa);
} else {
    $jumlah_pembelian = ($hasil_peramalan + 10) - $stok_akhir;
}
$jumlah_pembelian = max(1, round($jumlah_pembelian));

// Cegah pembelian jika 0
// if ($jumlah_pembelian <= 0) {
//     $_SESSION['alert'] = ['type' => 'warning', 'message' => 'Jumlah pembelian 0, tidak dapat disimpan.'];
//     header("Location: " . BASE_URL . "pages/pengadaan/pembelian.php");
//     exit;
// }

$harga_obat = getHargaObat($conn, $kode_obat); // Buat fungsi untuk ambil harga dari DB
$total_pembelian = $jumlah_pembelian * $harga_obat;

echo "Peramalan: $hasil_peramalan, Stok akhir: $stok_akhir, Kedaluwarsa: $stok_kedaluwarsa";

// Generate ID
$id_peramalan = generateIdPeramalan($conn); // pembelian/functions.php
$id_pembelian = generateIdPembelian($conn); // pembelian/functions.php

try {
    mysqli_begin_transaction($conn);

    mysqli_query($conn, "
    INSERT INTO peramalan (
            id_peramalan, 
            tgl_peramalan,
            bulan_peramalan, 
            hasil_peramalan, 
            mad_peramalan, 
            mape_peramalan, 
            kode_obat,
            id_user
        ) VALUES (
            '$id_peramalan',
            '$tgl_peramalan',
            '$bulan_peramalan',
            $hasil_peramalan,
            $mad,
            $mape,
            '$kode_obat',
            '$id_user'
        )
    ");

    mysqli_query($conn, "
    INSERT INTO pembelian (
            id_pembelian, 
            kode_obat, 
            id_supplier, 
            jml_pembelian, 
            total_pembelian, 
            id_peramalan,
            id_user
        ) VALUES (
            '$id_pembelian',
            '$kode_obat',
            '$id_supplier',
            $jumlah_pembelian,
            $total_pembelian,
            '$id_peramalan',
            '$id_user'
        )
    ");

    mysqli_commit($conn);
    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Data pembelian berhasil disimpan.'];
} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal menyimpan data pembelian: ' . $e->getMessage()];
}

header("Location: " . BASE_URL . "pages/pengadaan/pembelian.php");
exit;
