<?php
include '../../config/auth.php';
include '../../config/config.php';
include '../../config/db.php';
include '../../templates/header.php';
onlyAdmin();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// === QUERY ke DATABASE ===
$totalObat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM obat"))['total'];
$totalSupplier = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM supplier"))['total'];
$totalPengguna = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pengguna"))['total'];
$totalPenerimaan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM penerimaan_obat"))['total'];
$totalPenjualan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM penjualan_obat"))['total'];
$obatHampirHabis = mysqli_query($conn, "SELECT nama_obat, stok FROM obat WHERE stok <= 10 ORDER BY stok ASC LIMIT 5");
$obatKedaluwarsa = mysqli_query($conn, "SELECT nama_obat, tgl_kedaluwarsa FROM obat WHERE tgl_kedaluwarsa <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) ORDER BY tgl_kedaluwarsa ASC LIMIT 5");

// Grafik 5 obat terlaris
$obatTerlaris = mysqli_query($conn, "
    SELECT o.nama_obat, SUM(p.jml_jual) AS total_jual
    FROM penjualan_obat p
    JOIN obat o ON p.kode_obat = o.kode_obat
    GROUP BY p.kode_obat
    ORDER BY total_jual DESC
    LIMIT 5
");

// Grafik penerimaan per bulan (12 bulan terakhir)
$penerimaanPerBulan = mysqli_query($conn, "
    SELECT DATE_FORMAT(tgl_penerimaan, '%b %Y') AS bulan, SUM(jml_masuk) AS total
    FROM penerimaan_obat
    WHERE tgl_penerimaan >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY YEAR(tgl_penerimaan), MONTH(tgl_penerimaan)
    ORDER BY tgl_penerimaan ASC
");

// Grafik penjualan per bulan (12 bulan terakhir)
$penjualanPerBulan = mysqli_query($conn, "
    SELECT DATE_FORMAT(tgl_jual, '%b %Y') AS bulan, SUM(jml_jual) AS total
    FROM penjualan_obat
    WHERE tgl_jual >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY YEAR(tgl_jual), MONTH(tgl_jual)
    ORDER BY tgl_jual ASC
");

// Siapkan data untuk Chart.js
$labelObat = [];
$dataObat = [];
if ($obatTerlaris && mysqli_num_rows($obatTerlaris) > 0) {
    while ($row = mysqli_fetch_assoc($obatTerlaris)) {
        $labelObat[] = $row['nama_obat'];
        $dataObat[] = $row['total_jual'];
    }
} else {
    $labelObat = ['-'];
    $dataObat = [0];
}

$labelPenerimaan = [];
$dataPenerimaan = [];
if ($penerimaanPerBulan && mysqli_num_rows($penerimaanPerBulan) > 0) {
    while ($row = mysqli_fetch_assoc($penerimaanPerBulan)) {
        $labelPenerimaan[] = $row['bulan'];
        $dataPenerimaan[] = $row['total'];
    }
} else {
    $labelPenerimaan = ['-'];
    $dataPenerimaan = [0];
}

$labelPenjualan = $labelPenerimaan;
$dataPenjualan = [];
if ($penjualanPerBulan && mysqli_num_rows($penjualanPerBulan) > 0) {
    while ($row = mysqli_fetch_assoc($penjualanPerBulan)) {
        $dataPenjualan[] = $row['total'];
    }
} else {
    $dataPenjualan = [0];
}
?>

<!-- Wrapper Layout -->
<div class="layout-wrapper">

    <!-- Sidebar -->
    <?php include '../../templates/sidebar_administrator.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <div class="px-4 py-4">
            <h2 class="fw-semibold">Dashboard Admin</h2>
            <div class="row g-3 py-2">
                <div class="col-md-3">
                    <div class="card shadow-sm p-3">
                        <h6>Total Obat</h6>
                        <p class="fs-5 fw-semibold mb-0"><?= $totalObat ?> Jenis</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-3">
                        <h6>Total Supplier</h6>
                        <p class="fs-5 fw-semibold mb-0"><?= $totalSupplier ?> Supplier</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-3">
                        <h6>Total Pengguna</h6>
                        <p class="fs-5 fw-semibold mb-0"><?= $totalPengguna ?> User</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-3">
                        <h6>Total Penerimaan</h6>
                        <p class="fs-5 fw-semibold mb-0"><?= $totalPenerimaan ?> Transaksi</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm p-3">
                        <h6>Total Penjualan</h6>
                        <p class="fs-5 fw-semibold mb-0"><?= $totalPenjualan ?> Transaksi</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark fw-bold">Obat Hampir Habis</div>
                        <ul class="list-group list-group-flush">
                            <?php if ($obatHampirHabis && mysqli_num_rows($obatHampirHabis) > 0): ?>
                                <?php while ($obat = mysqli_fetch_assoc($obatHampirHabis)): ?>
                                    <li class="list-group-item"><?= $obat['nama_obat'] ?> (Stok: <?= $obat['stok'] ?>)</li>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <li class="list-group-item text-muted">Tidak ada data</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-header bg-danger text-white fw-bold">Obat Kedaluwarsa < 30 Hari</div>
                                <ul class="list-group list-group-flush">
                                    <?php if ($obatKedaluwarsa && mysqli_num_rows($obatKedaluwarsa) > 0): ?>
                                        <?php while ($obat = mysqli_fetch_assoc($obatKedaluwarsa)): ?>
                                            <li class="list-group-item"><?= $obat['nama_obat'] ?>
                                                (<?= date('d-m-Y', strtotime($obat['tgl_kedaluwarsa'])) ?>)</li>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <li class="list-group-item text-muted">Tidak ada data</li>
                                    <?php endif; ?>
                                </ul>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white fw-bold">5 Obat Terlaris</div>
                            <div class="card-body">
                                <canvas id="chartObatTerlaris" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-success text-white fw-bold">Grafik Penerimaan & Penjualan per
                                Bulan</div>
                            <div class="card-body">
                                <canvas id="chartTransaksiBulanan" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script>
    window.labelObat = <?= json_encode($labelObat) ?>;
    window.dataObat = <?= json_encode($dataObat) ?>;
    window.labelBulan = <?= json_encode($labelPenerimaan) ?>;
    window.dataPenerimaan = <?= json_encode($dataPenerimaan) ?>;
    window.dataPenjualan = <?= json_encode($dataPenjualan) ?>;
</script>
<script src="<?= BASE_URL ?>js/chart.js"></script>
<script src="<?= BASE_URL ?>js/active_menu.js"></script>
</body>

</html>