<?php
include '../../config/auth.php';
include '../../config/config.php';
include '../../config/db.php';
include '../../templates/header.php';
onlyAdmin();

// Ambil total data untuk statistik
$totalObat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM obat"))['total'];
$totalSupplier = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM supplier"))['total'];
$totalPengguna = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pengguna"))['total'];
$totalPenerimaan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM penerimaan_obat"))['total'];
$totalPenjualan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM detail_penjualan"))['total'];
$totalPembelian = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pembelian"))['total'];

// Hitung pengguna per role
$roles = ['Apoteker', 'Pengadaan', 'Pelayanan', 'Administrator'];
$penggunaPerRole = [];
foreach ($roles as $role) {
    $roleEscaped = mysqli_real_escape_string($conn, $role);
    $query = mysqli_query($conn, "SELECT COUNT(*) AS jumlah FROM pengguna WHERE role = '$roleEscaped'");
    $penggunaPerRole[$role] = mysqli_fetch_assoc($query)['jumlah'];
}

// Ambil data jenis obat untuk pie chart
$jenisObatQuery = mysqli_query($conn, "SELECT jenis, COUNT(*) AS jumlah FROM obat GROUP BY jenis");
$labelJenis = [];
$dataJenis = [];

while ($row = mysqli_fetch_assoc($jenisObatQuery)) {
    $labelJenis[] = $row['jenis'];
    $dataJenis[] = $row['jumlah'];
}
?>

<link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=<?= time(); ?>">

<div class="layout-wrapper">
    <?php include '../../templates/sidebar_administrator.php'; ?>

    <main class="main-content">
        <div class="px-3 py-2">

            <!-- Header -->
            <div class="bg-white shadow-sm p-4 mb-4 rounded fade-in">
                <h4 class="mb-1 fw-semibold">Selamat Datang, Administrator 👋</h4>
                <p class="text-muted mb-0">Berikut adalah ringkasan data penting dan akses cepat dalam sistem apotek.
                </p>
            </div>

            <!-- Grid utama -->
            <div class="row g-3 fade-in">

                <!-- Kolom kiri: statistik -->
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

                    <div class="row g-3 mt-2 fade-in">
                        <!-- Aksi Cepat -->
                        <div class="col-md-12">
                            <div class="card shadow-sm p-4 h-100 fade-in">
                                <h6 class="fw-semibold mb-4"><i class="bi bi-lightning-fill me-2 text-warning"></i>Aksi
                                    Cepat
                                </h6>
                                <div class="d-grid gap-3">
                                    <a href="<?= BASE_URL ?>pages/administrator/supplier.php"
                                        class="btn btn-outline-blue-dark btn-lg">
                                        <i class="bi bi-truck me-2"></i>Kelola Supplier
                                    </a>
                                    <a href="<?= BASE_URL ?>pages/administrator/obat.php"
                                        class="btn btn-outline-blue-light btn-lg">
                                        <i class="bi bi-capsule me-2"></i>Kelola Obat
                                    </a>
                                    <a href="<?= BASE_URL ?>pages/administrator/pengguna.php"
                                        class="btn btn-outline-green-light btn-lg">
                                        <i class="bi bi-person-badge me-2"></i>Kelola Pengguna
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Data Pengguna per Role -->
                <div class="col-md-4">
                    <div class="card shadow-sm p-4 h-100">
                        <h6 class="fw-semibold mb-4 mt-2"><i class="bi bi-pie-chart me-2 text-info"></i>Distribusi
                            Jenis Obat</h6>
                        <canvas id="jenisObatPieChart" width="600" height="500" class="w-100"
                            style="max-height: 400px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Section Tambahan -->
            <div class="row g-3 mt-3 fade-in">
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

<!-- Chart JS & Pie Data -->
<script>
    window.labelJenisObat = <?= json_encode($labelJenis); ?>;
    window.dataJenisObat = <?= json_encode($dataJenis); ?>;
</script>

<script src="<?= BASE_URL ?>js/pie_chart.js?v=<?= time(); ?>"></script>
<script src="<?= BASE_URL ?>js/active_menu.js"></script>
</body>

</html>