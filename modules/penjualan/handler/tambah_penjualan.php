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
    $id_user = $_SESSION['user']['id_user'];
    $kode_obat = $_POST['kode_obat'];
    $jml_terjual = (int) $_POST['jml_terjual'];

    if (!validasiInputPenjualan($tgl_penjualan, $jml_terjual)) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Tanggal atau jumlah tidak valid.'];
        header("Location: ../../../pages/pelayanan/penjualan.php");
        exit;
    }

    $penggunaan = kurangiStokFIFO($conn, $kode_obat, $jml_terjual);

    if (!$penggunaan['status']) {
        $sisa_stok = $penggunaan['sisa_stok'];
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => "Stok tidak cukup. Sisa stok untuk obat ini hanya $sisa_stok."
        ];
        header("Location: ../../../pages/pelayanan/penjualan.php");
        exit;
    }

    $data_penggunaan = $penggunaan['data'];

    $insertPenjualan = mysqli_query($conn, "
        INSERT INTO penjualan_obat (id_penjualan, tgl_penjualan, id_user) 
        VALUES ('$id_penjualan', '$tgl_penjualan', '$id_user')
    ");

    if (!$insertPenjualan) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal menyimpan penjualan.'];
        header("Location: ../../../pages/pelayanan/penjualan.php");
        exit;
    }

    foreach ($data_penggunaan as $pakai) {
        $id_detail = generateDetailId($conn);
        $id_stok = $pakai['id_stok'];
        $jml = $pakai['jml_terjual'];

        $insertDetail = mysqli_query($conn, "
            INSERT INTO detail_penjualan (id_detail, jml_terjual, id_penjualan, id_stok, kode_obat)
            VALUES ('$id_detail', $jml, '$id_penjualan', '$id_stok', '$kode_obat')
        ");

        if (!$insertDetail) {
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