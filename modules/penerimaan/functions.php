<?php
function validasiObatDanSupplier($conn, $kode_obat, $id_supplier)
{
    $cek_obat = mysqli_query($conn, "SELECT 1 FROM obat WHERE kode_obat = '$kode_obat'");
    $cek_supplier = mysqli_query($conn, "SELECT 1 FROM supplier WHERE id_supplier = '$id_supplier'");
    return mysqli_num_rows($cek_obat) && mysqli_num_rows($cek_supplier);
}

function tambahStok($conn, $id_penerimaan, $kode_obat, $jumlah)
{
    mysqli_query($conn, "INSERT INTO stok_obat (id_penerimaan, kode_obat, jml_stok) VALUES ('$id_penerimaan','$kode_obat', '$jumlah')");
}

function updateStokSetelahEdit($conn, $id_penerimaan, $jml_baru, $jml_lama)
{
    $selisih = $jml_baru - $jml_lama;
    $query = mysqli_query($conn, "UPDATE stok_obat 
        SET jml_stok = jml_stok + $selisih 
        WHERE id_penerimaan = '$id_penerimaan'");
    return $query !== false;
}

function hapusStok($conn, $id_penerimaan)
{
    mysqli_query($conn, "DELETE FROM stok_obat WHERE id_penerimaan = '$id_penerimaan'");
}

function ambilPenerimaan($conn, $id)
{
    $result = mysqli_query($conn, "SELECT * FROM penerimaan_obat WHERE id_penerimaan='$id'");
    return mysqli_fetch_assoc($result);
}

function generatePenerimaanId($conn)
{
    $query = mysqli_query($conn, "SELECT id_penerimaan FROM penerimaan_obat ORDER BY id_penerimaan DESC LIMIT 1");
    if ($data = mysqli_fetch_assoc($query)) {
        $lastIdNum = (int) substr($data['id_penerimaan'], 5);
        $newIdNum = $lastIdNum + 1;
    } else {
        $newIdNum = 1;
    }
    return 'PN-' . str_pad($newIdNum, 4, '0', STR_PAD_LEFT);
}

function validasiInputPenerimaan($tgl)
{
    return strtotime($tgl) <= strtotime(date('Y-m-d'));
}

function getDataEditPenerimaan($conn, $id_penerimaan)
{
    $result = mysqli_query($conn, "SELECT * FROM penerimaan_obat WHERE id_penerimaan = '$id_penerimaan'");
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}
