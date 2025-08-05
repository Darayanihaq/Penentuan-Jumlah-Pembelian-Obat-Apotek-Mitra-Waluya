<?php
include '../../config/config.php';
include_once __DIR__ . '/../../../config/db.php';

$bulanIndonesia = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
];

$bulan = (int) date('m');
$tahun = date('Y');
$nama_bulan = $bulanIndonesia[$bulan];

$query = "
    SELECT o.nama_obat, SUM(d.jml_terjual) AS total_terjual
    FROM detail_penjualan d
    JOIN penjualan_obat p ON d.id_penjualan = p.id_penjualan
    JOIN obat o ON d.kode_obat = o.kode_obat
    WHERE MONTH(p.tgl_penjualan) = $bulan AND YEAR(p.tgl_penjualan) = $tahun
    GROUP BY d.kode_obat
    ORDER BY total_terjual DESC
";
$result = mysqli_query($conn, $query);

$labels = [];
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['nama_obat'];
    $data[] = $row['total_terjual'];
}
