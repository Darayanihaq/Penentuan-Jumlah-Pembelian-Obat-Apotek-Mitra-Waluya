<?php
include '../../../config/db.php';

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=Laporan-Pembelian.csv");

$output = fopen("php://output", "w");

// Tambahkan kolom Harga Obat
fputcsv($output, ['No', 'Nama Obat', 'Harga', 'Jumlah Pembelian', 'Supplier', 'Bulan Pembelian']);

// Ambil data dari database
$query = "SELECT o.nama_obat, o.harga, p.jml_pembelian, s.nama_supplier, p.bulan_pembelian
          FROM pembelian p
          LEFT JOIN obat o ON o.kode_obat = p.kode_obat
          LEFT JOIN supplier s ON s.id_supplier = p.id_supplier";
$result = mysqli_query($conn, $query);

$no = 1;
while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, [
        $no++,
        $row['nama_obat'],
        $row['harga'],
        $row['jml_pembelian'],
        $row['nama_supplier'],
        $row['bulan_pembelian']
    ]);
}

fclose($output);
exit;
