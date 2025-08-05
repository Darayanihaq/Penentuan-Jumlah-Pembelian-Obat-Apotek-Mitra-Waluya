<?php
include '../../config/auth.php';
include '../../config/config.php';
include '../../config/db.php';
include '../../templates/header.php';
onlyPelayanan();

include '../../modules/beranda/handler/grafik_penjualan.php';
include '../../modules/beranda/handler/penjualan_terakhir.php';
?>

<div class="layout-wrapper">
    <?php include '../../templates/sidebar_pelayanan.php'; ?>

    <main class="main-content">
        <div class="px-3 py-2">

            <!-- Header -->
            <div class="bg-white shadow-sm p-4 mb-4 rounded fade-in">
                <h4 class="mb-1 fw-semibold">Selamat Datang, Bagian Pelayanan 👋</h4>
                <p class="text-muted mb-0">Berikut adalah ringkasan data penting dan akses cepat dalam sistem apotek.
                </p>
            </div>

            <div class="row g-4">
                <?php include '../../modules/beranda/partials/grafik_card.php'; ?>
                <?php include '../../modules/beranda/partials/penjualan_terakhir_card.php'; ?>
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