<?php
require_once __DIR__ . '/../../config/db.php';

function generatePenjualanId($conn)
{
    $query = mysqli_query($conn, "SELECT id_penjualan FROM penjualan_obat ORDER BY id_penjualan DESC LIMIT 1");
    if ($data = mysqli_fetch_assoc($query)) {
        $lastIdNum = (int) substr($data['id_penjualan'], 3);
        $newIdNum = $lastIdNum + 1;
    } else {
        $newIdNum = 1;
    }
    return 'PJ-' . str_pad($newIdNum, 3, '0', STR_PAD_LEFT);
}

function generateDetailId($conn)
{
    $query = mysqli_query($conn, "SELECT id_detail FROM detail_penjualan ORDER BY id_detail DESC LIMIT 1");
    if ($data = mysqli_fetch_assoc($query)) {
        $lastIdNum = (int) substr($data['id_detail'], 3);
        $newIdNum = $lastIdNum + 1;
    } else {
        $newIdNum = 1;
    }
    return 'DT-' . str_pad($newIdNum, 3, '0', STR_PAD_LEFT);
}

function updateStokSetelahEdit($conn, $id_penerimaan, $jml_baru, $jml_lama)
{
    $selisih = $jml_baru - $jml_lama;
    mysqli_query($conn, "UPDATE stok_obat SET jml_stok = jml_stok + $selisih WHERE id_penerimaan = '$id_penerimaan'");
}

function tambahStokKembali($conn, $id_stok, $jumlah)
{
    // Log sebelum update
    $before = mysqli_query($conn, "SELECT jml_stok FROM stok_obat WHERE id_stok = '$id_stok'");
    $before_stok = mysqli_fetch_assoc($before)['jml_stok'];

    $query = "UPDATE stok_obat SET jml_stok = jml_stok + $jumlah WHERE id_stok = '$id_stok'";
    $result = mysqli_query($conn, $query);

    // Log setelah update
    if ($result) {
        $after = mysqli_query($conn, "SELECT jml_stok FROM stok_obat WHERE id_stok = '$id_stok'");
        $after_stok = mysqli_fetch_assoc($after)['jml_stok'];
        error_log("Stok Update - ID: $id_stok, Before: $before_stok, Added: $jumlah, After: $after_stok");
    } else {
        error_log("Stok Update Failed - ID: $id_stok, Error: " . mysqli_error($conn));
    }

    return $result;
}

function validasiInputPenjualan($tgl_penjualan, $jml_terjual)
{
    $today = date('Y-m-d');
    if ($tgl_penjualan > $today) {
        return false;
    }
    if ($jml_terjual < 1) {
        return false;
    }
    return true;
}


function cekStokObatFIFO($conn, $kode_obat, $jumlah)
{
    $query = "
        SELECT s.id_stok, s.jml_stok, p.tgl_penerimaan
        FROM stok_obat s
        JOIN penerimaan_obat p ON s.id_penerimaan = p.id_penerimaan
        JOIN obat o ON p.kode_obat = o.kode_obat
        WHERE o.kode_obat = '$kode_obat' 
          AND s.jml_stok > 0
          AND p.tgl_kedaluwarsa > DATE_ADD(CURDATE(), INTERVAL 30 DAY)
        ORDER BY p.tgl_penerimaan ASC
    ";

    $result = mysqli_query($conn, $query);
    if (!$result) {
        return ['cukup' => false, 'stok_data' => [], 'error' => mysqli_error($conn)];
    }

    $stok_data = [];
    $total = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $stok_data[] = $row;
        $total += $row['jml_stok'];
        if ($total >= $jumlah)
            break;
    }

    return ['cukup' => $total >= $jumlah, 'stok_data' => $stok_data];
}


function kurangiStokFIFO($conn, $kode_obat, $jumlah)
{
    $stok_info = cekStokObatFIFO($conn, $kode_obat, $jumlah);
    if (!$stok_info['cukup'])
        return false;

    $stok_data = $stok_info['stok_data'];
    $sisa = $jumlah;
    $penggunaan = [];

    foreach ($stok_data as $stok) {
        if ($sisa <= 0)
            break;

        $pakai = min($stok['jml_stok'], $sisa);
        $new_stok = $stok['jml_stok'] - $pakai;

        $update = mysqli_query($conn, "UPDATE stok_obat SET jml_stok = $new_stok WHERE id_stok = {$stok['id_stok']}");
        if (!$update) {
            // rollback manual jika terjadi error update stok
            error_log("Gagal update stok_obat ID {$stok['id_stok']}: " . mysqli_error($conn));
            return false;
        }

        $penggunaan[] = [
            'id_stok' => $stok['id_stok'],
            'jml_terjual' => $pakai
        ];

        $sisa -= $pakai;
    }

    return $penggunaan;
}

function rollbackSemuaDetailPenjualan($conn, $id_penjualan)
{
    $query = mysqli_query($conn, "SELECT id_stok, jml_terjual FROM detail_penjualan WHERE id_penjualan = '$id_penjualan'");
    while ($row = mysqli_fetch_assoc($query)) {
        tambahStokKembali($conn, $row['id_stok'], $row['jml_terjual']);
    }
    return true;
}

function hapusDetailPenjualan($conn, $id_detail)
{
    // Ambil info stok dan jumlah terjual
    $query = mysqli_query($conn, "
        SELECT id_stok, jml_terjual 
        FROM detail_penjualan 
        WHERE id_detail = '$id_detail'
    ");

    $data = mysqli_fetch_assoc($query);

    // Kembalikan stok
    tambahStokKembali($conn, $data['id_stok'], $data['jml_terjual']);

    // Hapus baris detail_penjualan saja
    mysqli_query($conn, "DELETE FROM detail_penjualan WHERE id_detail = '$id_detail'");
}
