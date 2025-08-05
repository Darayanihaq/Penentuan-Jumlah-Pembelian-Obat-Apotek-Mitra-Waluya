<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../config/db.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/../../../config/auth.php';
onlyPelayanan();

$id_penjualan = $_POST['id_penjualan'];
$tgl_penjualan = $_POST['tgl_penjualan'];
$kode_obat = $_POST['kode_obat'];
$jml_terjual_baru = (int) $_POST['jml_terjual'];
$id_user = $_SESSION['user']['id_user'];

if (!validasiInputPenjualan($tgl_penjualan, $jml_terjual_baru)) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Tanggal tidak valid.'];
    header("Location: ../../../pages/pelayanan/penjualan.php");
    exit;
}

mysqli_begin_transaction($conn);

try {
    $old = mysqli_query($conn, "SELECT id_stok, jml_terjual FROM detail_penjualan WHERE id_penjualan = '$id_penjualan'");
    while ($row = mysqli_fetch_assoc($old)) {
        if (!tambahStokKembali($conn, $row['id_stok'], $row['jml_terjual'])) {
            throw new Exception("Gagal mengembalikan stok lama.");
        }
    }

    if (!mysqli_query($conn, "DELETE FROM detail_penjualan WHERE id_penjualan = '$id_penjualan'")) {
        throw new Exception("Gagal menghapus detail lama.");
    }

    if (!mysqli_query($conn, "UPDATE penjualan_obat SET tgl_penjualan = '$tgl_penjualan' WHERE id_penjualan = '$id_penjualan'")) {
        throw new Exception("Gagal memperbarui tanggal penjualan.");
    }

    $stok_check = cekStokObatFIFO($conn, $kode_obat, $jml_terjual_baru);
    if (!$stok_check['cukup'] || empty($stok_check['stok_data'])) {
        throw new Exception("Stok tidak mencukupi untuk jumlah baru.");
    }

    $sisa = $jml_terjual_baru;
    foreach ($stok_check['stok_data'] as $stok) {
        if ($sisa <= 0)
            break;

        $pakai = min($stok['jml_stok'], $sisa);
        $id_detail = generateDetailId($conn);

        $queryDetail = "INSERT INTO detail_penjualan 
            (id_detail, jml_terjual, id_penjualan, id_stok, kode_obat) 
            VALUES ('$id_detail', $pakai, '$id_penjualan', {$stok['id_stok']}, '$kode_obat')";
        if (!mysqli_query($conn, $queryDetail)) {
            throw new Exception("Gagal menyimpan detail penjualan baru.");
        }

        $queryKurangi = "UPDATE stok_obat SET jml_stok = jml_stok - $pakai 
            WHERE id_stok = {$stok['id_stok']}";
        if (!mysqli_query($conn, $queryKurangi)) {
            throw new Exception("Gagal mengurangi stok.");
        }

        $sisa -= $pakai;
    }

    mysqli_commit($conn);
    $_SESSION['alert'] = ['type' => 'success', 'message' => 'Penjualan berhasil diubah.'];

} catch (Exception $e) {
    mysqli_rollback($conn);
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal mengubah penjualan: ' . $e->getMessage()];
}

header("Location: ../../../pages/pelayanan/penjualan.php");
exit;