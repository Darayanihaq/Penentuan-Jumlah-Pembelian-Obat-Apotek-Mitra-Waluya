<?php
function ambilStokAkhir(mysqli $conn, string $kode_obat): int
{
    $query = "
        SELECT SUM(s.jml_stok) AS total
        FROM stok_obat s
        JOIN obat o ON s.kode_obat = o.kode_obat
        WHERE o.kode_obat = ?
    ";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $kode_obat);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    return (int) ($data['total'] ?? 0);
}


function ambilStokKedaluwarsa(mysqli $conn, string $kode_obat, int $bulan_terakhir = 4): int
{
    $batas_waktu = date('Y-m-d', strtotime("-$bulan_terakhir months"));

    $query = "
        SELECT SUM(s.jml_stok) as total
        FROM stok_obat s
        JOIN penerimaan_obat p ON s.id_penerimaan = p.id_penerimaan
        WHERE s.kode_obat = ?
          AND s.status_stok = 'Kedaluwarsa'
          AND p.tgl_penerimaan >= ?
    ";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $kode_obat, $batas_waktu);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    return (int) ($data['total'] ?? 0);
}

function generateIdPeramalan(mysqli $conn): string
{
    $result = mysqli_query($conn, "SELECT MAX(id_peramalan) AS max_id FROM peramalan");
    $data = mysqli_fetch_assoc($result);
    $lastId = isset($data['max_id']) ? (int) substr($data['max_id'], 3) : 0;
    return 'PRM' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
}

function generateIdPembelian(mysqli $conn): string
{
    $result = mysqli_query($conn, "SELECT MAX(id_pembelian) AS max_id FROM pembelian");
    $data = mysqli_fetch_assoc($result);
    $lastId = isset($data['max_id']) ? (int) substr($data['max_id'], 2) : 0;
    return 'PB' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
}

function getHargaObat($conn, $kode_obat)
{
    $query = mysqli_query($conn, "SELECT harga_obat FROM obat WHERE kode_obat = '$kode_obat'");
    if (!$query || mysqli_num_rows($query) == 0) {
        die("Harga obat tidak ditemukan.");
    }

    $data = mysqli_fetch_assoc($query);
    return $data['harga_obat'];
}




