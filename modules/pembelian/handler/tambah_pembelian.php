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

$tahun_pembelian = (int) $_POST['tahun_pembelian'];
$bulan_pembelian = $_POST['bulan_pembelian'];
$bulan_peramalan = $bulan_pembelian;
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

// Gunakan fungsi yang sudah ada untuk WMA
$forecast_data = hitungWMAArray($penjualan);
$hasil_peramalan = end($forecast_data); // Ambil forecast terakhir

// Hitung MAD (bandingkan data aktual dengan forecast)
$data_aktual_mad = $penjualan;
$mad = hitungMAD($data_aktual_mad, $forecast_data);

// Hitung MAPE (bandingkan data aktual dengan forecast)
$data_aktual_mape = $penjualan;
$mape = hitungMAPE($data_aktual_mape, $forecast_data);
$jumlah_pembelian = $hasil_peramalan;

$stok_akhir = ambilStokAkhir($conn, $kode_obat);
$stok_kedaluwarsa = ambilStokKedaluwarsa($conn, $kode_obat);

if ($stok_akhir == 0 && $stok_kedaluwarsa == 0) {
    $jumlah_pembelian = $hasil_peramalan + 10;
} elseif ($stok_kedaluwarsa > 0) {
    $jumlah_pembelian = ($hasil_peramalan + 10) - ($stok_akhir - $stok_kedaluwarsa);
} else {
    $jumlah_pembelian = ($hasil_peramalan + 10) - $stok_akhir;
}
$jumlah_pembelian = max(1, round($jumlah_pembelian));

$harga_obat = getHargaObat($conn, $kode_obat);
$total_pembelian = $jumlah_pembelian * $harga_obat;

echo "Peramalan: $hasil_peramalan, Stok akhir: $stok_akhir, Kedaluwarsa: $stok_kedaluwarsa";

$id_peramalan = generateIdPeramalan($conn);
$id_pembelian = generateIdPembelian($conn);

try {
    mysqli_begin_transaction($conn);

    mysqli_query($conn, "
    INSERT INTO peramalan (
            id_peramalan, 
            bulan_peramalan, 
            hasil_peramalan, 
            mad_peramalan, 
            mape_peramalan, 
            kode_obat,
            id_user
        ) VALUES (
            '$id_peramalan',
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
            bulan_pembelian,
            tahun_pembelian,
            jml_pembelian, 
            total_pembelian, 
            id_peramalan,
            id_user
        ) VALUES (
            '$id_pembelian',
            '$kode_obat',
            '$id_supplier',
            '$bulan_pembelian',
            $tahun_pembelian,
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
