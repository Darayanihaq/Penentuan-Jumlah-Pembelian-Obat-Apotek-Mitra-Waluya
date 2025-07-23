<?php

function getRiwayatPenjualanObat(mysqli $conn, string $kode_obat): array
{
    $query = "
        SELECT 
            DATE_FORMAT(p.tgl_penjualan, '%Y-%m') AS bulan,
            SUM(dp.jml_terjual) AS jml_terjual
        FROM detail_penjualan dp
        JOIN penjualan_obat p ON p.id_penjualan = dp.id_penjualan
        WHERE dp.kode_obat = ?
        GROUP BY bulan
        ORDER BY bulan DESC
        LIMIT 4
    ";

    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        echo "Gagal prepare query getRiwayatPenjualanObat: " . mysqli_error($conn);
        return [];
    }

    mysqli_stmt_bind_param($stmt, 's', $kode_obat);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $penjualan = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $penjualan[] = (int) $row['jml_terjual'];
    }

    return array_reverse($penjualan); // untuk WMA: data lama dulu
}
