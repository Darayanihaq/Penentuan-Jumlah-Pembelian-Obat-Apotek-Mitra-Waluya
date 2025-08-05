<?php
$query = "
                SELECT 
                    p.id_penjualan, 
                    p.tgl_penjualan, 
                    d.id_detail, 
                    d.jml_terjual, 
                    o.nama_obat, 
                    o.jenis,
                    r.no_batch
                FROM penjualan_obat p
                JOIN detail_penjualan d ON p.id_penjualan = d.id_penjualan
                JOIN obat o ON d.kode_obat = o.kode_obat
                JOIN stok_obat s ON d.id_stok = s.id_stok
                JOIN penerimaan_obat r ON s.id_penerimaan = r.id_penerimaan
                WHERE 1
            ";

if (!empty($_GET['bulan'])) {
    $bulan = (int) $_GET['bulan'];
    $query .= " AND MONTH(p.tgl_penjualan) = $bulan";
}

if (!empty($_GET['tahun'])) {
    $tahun = (int) $_GET['tahun'];
    $query .= " AND YEAR(p.tgl_penjualan) = $tahun";
}

$query .= " ORDER BY p.tgl_penjualan DESC";

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $result = mysqli_query($conn, $query);
} catch (Exception $e) {
    die("Query error: " . $e->getMessage());
}

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
?>

<form method="GET" class="row g-2 mb-3" style="padding-top: 15px;">
    <div class="col-md-1">
        <select name="bulan" id="bulan" class="form-select">
            <option value="">Bulan</option>
            <?php
            foreach ($bulanIndonesia as $num => $namaBulan) {
                $selected = (isset($_GET['bulan']) && $_GET['bulan'] == $num) ? 'selected' : '';
                echo "<option value='$num' $selected>$namaBulan</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-1">
        <select name="tahun" id="tahun" class="form-select">
            <option value="">Tahun</option>
            <?php
            $tahunSekarang = date('Y');
            for ($t = $tahunSekarang; $t >= $tahunSekarang - 5; $t--) {
                $selected = (isset($_GET['tahun']) && $_GET['tahun'] == $t) ? 'selected' : '';
                echo "<option value='$t' $selected>$t</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-2 align-self-end">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="penjualan.php" class="btn btn-secondary">Reset</a>
    </div>
</form>

<div class="table-responsive mt-3">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-light">
            <tr class="text-center">
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Obat</th>
                <th>Jenis</th>
                <th>Jumlah Terjual</th>
                <th>Batch</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= date('d-m-Y', strtotime($row['tgl_penjualan'])) ?></td>
                    <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                    <td><?= htmlspecialchars($row['jenis']) ?></td>
                    <td class="text-center"><?= $row['jml_terjual'] ?></td>
                    <td><?= htmlspecialchars($row['no_batch']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>