<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../../config/auth.php';
onlyPelayanan();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_penjualan = generatePenjualanId($conn);
    $tgl_penjualan = $_POST['tgl_penjualan'];
    $kode_obat = $_POST['kode_obat'];
    $jml_terjual = (int) $_POST['jml_terjual'];

    if (!validasiInputPenjualan($tgl_penjualan, $jml_terjual)) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Input tidak valid.'];
        header("Location: ../../../pages/pelayanan/penjualan.php");
        exit;
    }

    $penggunaan = kurangiStokFIFO($conn, $kode_obat, $jml_terjual);
    if ($penggunaan === false) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Stok tidak cukup untuk obat yang dipilih.'];
        header("Location: ../../../pages/pelayanan/penjualan.php");
        exit;
    }

    // Simpan ke tabel penjualan_obat
    $insertPenjualan = mysqli_query($conn, "
        INSERT INTO penjualan_obat (id_penjualan, tgl_penjualan) 
        VALUES ('$id_penjualan', '$tgl_penjualan')
    ");

    if (!$insertPenjualan) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal menyimpan penjualan.'];
        header("Location: ../../../pages/pelayanan/penjualan.php");
        exit;
    }

    // Simpan detail per batch (penggunaan stok)
    foreach ($penggunaan as $pakai) {
        $id_detail = generateDetailId($conn);
        $id_stok = $pakai['id_stok'];
        $jml = $pakai['jml_terjual'];

        $insertDetail = mysqli_query($conn, "
            INSERT INTO detail_penjualan (id_detail, jml_terjual, id_penjualan, id_stok, kode_obat)
            VALUES ('$id_detail', $jml, '$id_penjualan', '$id_stok', '$kode_obat')
        ");

        if (!$insertDetail) {
            // Jika gagal simpan detail, rollback stok dan hapus transaksi
            rollbackSemuaDetailPenjualan($conn, $id_penjualan);
            mysqli_query($conn, "DELETE FROM penjualan_obat WHERE id_penjualan = '$id_penjualan'");
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal menyimpan detail penjualan.'];
            header("Location: ../../../pages/pelayanan/penjualan.php");
            exit;
        }
    }

    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Penjualan berhasil disimpan.'];
    header("Location: ../../../pages/pelayanan/penjualan.php");
    exit;
}