<?php

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

// Query dasar
$query = "
    SELECT p.*, o.nama_obat, o.jenis, o.harga_obat, s.nama_supplier, r.bulan_peramalan, r.hasil_peramalan, r.mad_peramalan, r.mape_peramalan
    FROM pembelian p
    JOIN obat o ON p.kode_obat = o.kode_obat
    JOIN supplier s ON p.id_supplier = s.id_supplier
    LEFT JOIN peramalan r ON p.id_peramalan = r.id_peramalan
    WHERE 1
";

// Filter bulan
if (!empty($_GET['bulan'])) {
    $bulan = (int) $_GET['bulan'];
    $nama_bulan = $bulanIndonesia[$bulan];
    $query .= " AND p.bulan_pembelian = '" . mysqli_real_escape_string($conn, $nama_bulan) . "'";
}

// Filter tahun
if (!empty($_GET['tahun'])) {
    $tahun = (int) $_GET['tahun'];
    $query .= " AND p.tahun_pembelian = $tahun";
}

// Urutkan
$query .= " ORDER BY p.tahun_pembelian DESC, FIELD(p.bulan_pembelian, 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember') DESC";

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $result = mysqli_query($conn, $query);
} catch (Exception $e) {
    die("Query error: " . $e->getMessage());
}
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
        <a href="pembelian.php" class="btn btn-secondary">Reset</a>
    </div>
</form>

<div class="table-responsive mt-3">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-light">
            <tr class="text-center">
                <th>No</th>
                <th>Pembelian</th>
                <th>Obat</th>
                <th>Jenis</th>
                <th>Supplier</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            if ($result && mysqli_num_rows($result) > 0):
                while ($row = mysqli_fetch_assoc($result)):
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td>
                            <?= (!empty($row['bulan_pembelian']) && !empty($row['tahun_pembelian']))
                                ? htmlspecialchars("{$row['bulan_pembelian']} - {$row['tahun_pembelian']}")
                                : '-' ?>
                        </td>

                        <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                        <td><?= htmlspecialchars($row['jenis']) ?></td>
                        <td><?= htmlspecialchars($row['nama_supplier']) ?></td>
                        <td class="text-center"><?= $row['jml_pembelian'] ?></td>
                        <td class="text-end">Rp <?= number_format($row['jml_pembelian'] * $row['harga_obat'], 0, ',', '.') ?>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalDetailPembelian"
                                data-id="<?= $row['id_pembelian'] ?>" data-kode-obat="<?= $row['kode_obat'] ?>"
                                data-nama-obat="<?= $row['nama_obat'] ?>" data-jumlah="<?= $row['jml_pembelian'] ?>"
                                data-bulan="<?= $row['bulan_peramalan'] ?>" data-hasil="<?= $row['hasil_peramalan'] ?>"
                                data-mad="<?= $row['mad_peramalan'] ?>" data-mape="<?= $row['mape_peramalan'] ?>">
                                <i class="bi bi-list"></i>
                            </button>
                            <button onclick="confirmDelete('<?= $row['id_peramalan'] ?>', '<?= $row['id_pembelian'] ?>')"
                                class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endwhile;
            else:
                ?>
                <tr>
                    <td colspan="9" class="text-center text-muted">Tidak ada data pembelian</td>
                </tr>
                <?php
            endif;
            ?>
        </tbody>
    </table>
</div>

<script>
    function confirmDelete(idPeramalan, idPembelian) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data yang dihapus tidak dapat dikembalikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= BASE_URL ?>modules/pembelian/handler/hapus_pembelian.php?id_peramalan=" + idPeramalan + "&id_pembelian=" + idPembelian;
            }
        });
    }
</script>