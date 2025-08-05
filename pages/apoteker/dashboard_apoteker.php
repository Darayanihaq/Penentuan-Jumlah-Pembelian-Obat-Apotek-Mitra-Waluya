<?php
include '../../config/auth.php';
include '../../config/config.php';
include '../../config/db.php';
include '../../templates/header.php';
onlyApoteker();

include '../../modules/beranda/handler/grafik_penjualan.php';

$totalObat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM obat"))['total'];
$totalSupplier = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM supplier"))['total'];
$totalPengguna = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pengguna"))['total'];

$totalPenerimaan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM penerimaan_obat"))['total'];
$totalPenjualan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM detail_penjualan"))['total'];
$totalPembelian = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pembelian"))['total'];

$chartQuery = mysqli_query($conn, "
    SELECT 
        o.nama_obat,
        MONTH(p.tgl_kedaluwarsa) AS bulan,
        YEAR(p.tgl_kedaluwarsa) AS tahun,
        SUM(s.jml_stok) AS total_kadaluwarsa
    FROM stok_obat s
    JOIN obat o ON s.kode_obat = o.kode_obat
    JOIN penerimaan_obat p ON s.id_penerimaan = p.id_penerimaan
    WHERE s.status_stok = 'Kedaluwarsa'
    GROUP BY o.nama_obat, tahun, bulan
    ORDER BY tahun DESC, bulan DESC, total_kadaluwarsa DESC
") or die(mysqli_error($conn));

$chartData = [];
while ($row = mysqli_fetch_assoc($chartQuery)) {
    $bulanTahun = date('M Y', mktime(0, 0, 0, $row['bulan'], 1, $row['tahun']));
    if (!isset($chartData[$bulanTahun])) {
        $chartData[$bulanTahun] = [];
    }
    $chartData[$bulanTahun][] = [
        'obat' => $row['nama_obat'],
        'jumlah' => $row['total_kadaluwarsa']
    ];
}
?>

<link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time(); ?>">

<div class="layout-wrapper">
    <?php include '../../templates/sidebar_apoteker.php'; ?>

    <main class="main-content">
        <div class="px-3 py-2">

            <div class="bg-white shadow-sm p-4 mb-4 rounded fade-in">
                <h4 class="mb-1 fw-semibold">Selamat Datang, Apoteker 👋</h4>
                <p class="text-muted mb-0">Berikut adalah ringkasan data penting dan akses cepat dalam sistem apotek.
                </p>
            </div>

            <div class="row g-3 fade-in">
                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="card bg-white text-dark px-3 py-4 h-100 card-hover">
                                <h6 class="mb-1"><i class="bi bi-capsule me-2"></i>Total Obat</h6>
                                <p class="fs-5 fw-semibold mb-0"><?= $totalObat ?> Obat</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-white text-dark px-3 py-4 h-100 card-hover">
                                <h6 class="mb-1"><i class="bi bi-truck me-2"></i>Total Supplier</h6>
                                <p class="fs-5 fw-semibold mb-0"><?= $totalSupplier ?> Supplier</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-white text-dark px-3 py-4 h-100 card-hover">
                                <h6 class="mb-1"><i class="bi bi-people-fill me-2"></i>Total Pengguna</h6>
                                <p class="fs-5 fw-semibold mb-0"><?= $totalPengguna ?> User</p>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-md-4">
                            <div class="card bg-white text-dark px-3 py-4 h-100 card-hover">
                                <h6 class="mb-1"><i class="bi bi-box-arrow-in-down me-2"></i>Penerimaan Obat</h6>
                                <p class="fs-5 fw-semibold mb-0"><?= $totalPenerimaan ?> Data</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-white text-dark px-3 py-4 h-100 card-hover">
                                <h6 class="mb-1"><i class="bi bi-cart-check me-2"></i>Penjualan Obat</h6>
                                <p class="fs-5 fw-semibold mb-0"><?= $totalPenjualan ?> Data</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-white text-dark px-3 py-4 h-100 card-hover">
                                <h6 class="mb-1"><i class="bi bi-bag-check me-2"></i>Pembelian Obat</h6>
                                <p class="fs-5 fw-semibold mb-0"><?= $totalPembelian ?> Data</p>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm p-4 mt-4">
                        <h5 class="mb-3">Grafik Penjualan Obat - Bulan <?= $nama_bulan . ' ' . $tahun ?></h5>
                        <canvas id="lineChartObat" height="400"></canvas>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm p-4 fade-in">
                        <h6 class="fw-semibold mb-4"><i class="bi bi-lightning-fill me-2 text-warning"></i>Aksi
                            Cepat
                        </h6>
                        <div class="d-grid gap-4">
                            <a href="<?= BASE_URL ?>pages/apoteker/penerimaan.php"
                                class="btn btn-outline-blue-dark btn-lg">
                                <i class="bi bi-truck me-2"></i>Penerimaan Obat
                            </a>
                            <a href="<?= BASE_URL ?>pages/apoteker/penjualan.php"
                                class="btn btn-outline-blue-light btn-lg">
                                <i class="bi bi-capsule me-2"></i>Penjualan Obat
                            </a>
                            <a href="<?= BASE_URL ?>pages/apoteker/pembelian.php"
                                class="btn btn-outline-green-light btn-lg">
                                <i class="bi bi-person-badge me-2"></i>Pembelian Obat
                            </a>
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="card shadow-sm p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Grafik Obat Kadaluwarsa / Bulan</h5>
                                <select id="bulanPicker" class="form-select w-auto">
                                    <?php foreach (array_keys($chartData ?? []) as $bulan): ?>
                                        <option value="<?= $bulan ?>"><?= $bulan ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <canvas id="chartKadaluwarsa" height="327"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-2 fade-in">
                <div class="col-md-12">
                    <div class="card shadow-sm p-4">
                        <h6 class="fw-semibold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Sistem
                        </h6>
                        <ul class="mb-0">
                            <li>Sistem berjalan sejak <strong>Mei 2025</strong>.</li>
                            <li>Total pengguna aktif: <?= $totalPengguna ?></li>
                            <li>Pengembangan terakhir: <strong>Agustus 2025</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartData = <?= json_encode($chartData) ?>;
</script>
<script src="<?= BASE_URL ?>js/gantchart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        initKadaluwarsaChart(chartData);
    });
</script>

<script type="module">
    import { renderChart } from '<?= BASE_URL ?>js/line_chart.js';

    const labels = <?= json_encode($labels); ?>;
    const data = <?= json_encode($data); ?>;
    const monthLabel = "<?= $nama_bulan . ' ' . $tahun ?>";

    renderChart(labels, data, monthLabel);
</script>
<script src="<?= BASE_URL ?>js/chart.js"></script>
<script src="<?= BASE_URL ?>js/active_menu.js"></script>
</body>

</html>