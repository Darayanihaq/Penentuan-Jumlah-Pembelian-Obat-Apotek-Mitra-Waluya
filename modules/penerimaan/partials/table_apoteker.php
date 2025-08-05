<?php
$query = "
                SELECT p.*, o.nama_obat, o.jenis, s.nama_supplier
                FROM penerimaan_obat p
                JOIN obat o ON p.kode_obat = o.kode_obat
                JOIN supplier s ON p.id_supplier = s.id_supplier
                WHERE 1
            ";

if (!empty($_GET['bulan'])) {
    $bulan = (int) $_GET['bulan'];
    $query .= " AND MONTH(p.tgl_penerimaan) = $bulan";
}

if (!empty($_GET['tahun'])) {
    $tahun = (int) $_GET['tahun'];
    $query .= " AND YEAR(p.tgl_penerimaan) = $tahun";
}

$query .= " ORDER BY p.tgl_penerimaan DESC";
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
        <a href="penerimaan.php" class="btn btn-secondary">Reset</a>
    </div>
</form>

<div class="table-responsive mt-3">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-light">
            <tr class="text-center">
                <th>No</th>
                <th>Supplier</th>
                <th>Obat</th>
                <th>Jenis</th>
                <th>Tanggal Penerimaan</th>
                <th>No Batch</th>
                <th>Tanggal Kedaluwarsa</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)):
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= $row['nama_supplier'] ?></td>
                    <td><?= $row['nama_obat'] ?></td>
                    <td><?= $row['jenis'] ?></td>
                    <td><?= $row['tgl_penerimaan'] ?></td>
                    <td><?= $row['no_batch'] ?></td>
                    <td><?= $row['tgl_kedaluwarsa'] ?></td>
                    <td class="text-center"><?= $row['jml_masuk'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>