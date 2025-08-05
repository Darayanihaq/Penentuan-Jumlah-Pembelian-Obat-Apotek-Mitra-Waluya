<?php
include '../../config/auth.php';
include '../../config/config.php';
include '../../config/db.php';
include '../../templates/header.php';
onlyPengadaan();

$totalHampirHabis = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM stok_obat
    WHERE status_stok = 'Hampir Habis'
") or die('Query Hampir Habis Error: ' . mysqli_error($conn));

$dataHampirHabis = mysqli_fetch_assoc($totalHampirHabis);

$totalKadaluwarsa = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM stok_obat
    WHERE status_stok = 'Kedaluwarsa'
") or die('Query Kadaluwarsa Error: ' . mysqli_error($conn));
$dataKadaluwarsa = mysqli_fetch_assoc($totalKadaluwarsa);

$totalTersedia = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM stok_obat
    WHERE status_stok = 'Tersedia'
") or die('Query Tersedia Error: ' . mysqli_error($conn));
$dataTersedia = mysqli_fetch_assoc($totalTersedia);

$totalKosong = mysqli_query($conn, "
    SELECT COUNT(*) AS total
    FROM stok_obat
    WHERE status_stok = 'Stok Habis'
") or die('Query Kosong Error: ' . mysqli_error($conn));
$dataKosong = mysqli_fetch_assoc($totalKosong);

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

<div class="layout-wrapper">
    <?php include '../../templates/sidebar_pengadaan.php'; ?>

    <main class="main-content bg-light">
        <div class="px-3 py-2">

            <div class="bg-white shadow-sm p-4 mb-4 rounded fade-in">
                <h4 class="mb-1 fw-semibold">Selamat Datang, Penanggung Jawab Pengadaan 👋</h4>
                <p class="text-muted mb-0">Berikut adalah ringkasan data penting dan akses cepat dalam sistem apotek.
                </p>
            </div>

            <div class="row g-3 fade-in">
                <!-- Kartu Ringkasan -->
                <div class="col-md-8">
                    <div class="row g-4">
                        <div class="col-md-6 col-lg-3">
                            <div class="card border-start border-4 border-success shadow-sm py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-check-circle-fill text-success fs-2"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Tersedia</h6>
                                        <h5 class="fw-bold mb-0"><?= $dataTersedia['total'] ?> Obat</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="card border-start border-4 border-warning shadow-sm py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-exclamation-triangle-fill text-warning fs-2"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Stok Rendah</h6>
                                        <h5 class="fw-bold mb-0"><?= $dataHampirHabis['total'] ?> Obat</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="card border-start border-4 border-secondary shadow-sm py-3 px-4">

                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-x-octagon-fill text-secondary fs-2"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Kedaluwarsa</h6>
                                        <h5 class="fw-bold mb-0"><?= $dataKadaluwarsa['total'] ?> Obat</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <div class="card border-start border-4 border-danger shadow-sm py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-slash-circle-fill text-danger fs-2"></i>
                                    </div>
                                    <div>
                                        <h6 class="text-muted mb-1">Stok Kosong</h6>
                                        <h5 class="fw-bold mb-0"><?= $dataKosong['total'] ?> Obat</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Grafik -->
                    <div class="card shadow-sm mt-4 p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-semibold text-secondary">Grafik Obat Kadaluwarsa / Bulan</h5>
                            <select id="bulanPicker" class="form-select w-auto">
                                <?php foreach (array_keys($chartData) as $bulan): ?>
                                    <option value="<?= $bulan ?>"><?= $bulan ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <canvas id="chartKadaluwarsa" height="300"></canvas>
                    </div>
                </div>

                <!-- Penerimaan Terakhir -->
                <div class="col-md-4 h-100">
                    <?php include '../../modules/beranda/handler/penerimaan_terakhir.php'; ?>
                    <?php include '../../modules/beranda/partials/penerimaan_terakhir_card.php'; ?>
                </div>
            </div>
            <div class="row g-3 mt-3 fade-in">
                <div class="col-md-12">
                    <div class="card shadow-sm p-4">
                        <h6 class="fw-semibold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Sistem
                        </h6>
                        <ul class="mb-0">
                            <li>Sistem berjalan sejak <strong>Mei 2025</strong>.</li>
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
<script src="<?= BASE_URL ?>js/active_menu.js"></script>
</body>

</html>