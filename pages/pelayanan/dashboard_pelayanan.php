<?php
include '../../config/auth.php';
include '../../config/config.php';
include '../../config/db.php';
include '../../templates/header.php';
onlyPelayanan();

$bulan = date('m');
$tahun = date('Y');

$query = "
SELECT o.nama_obat, SUM(d.jml_terjual) AS total_terjual
FROM detail_penjualan d
JOIN penjualan_obat p ON d.id_penjualan = p.id_penjualan
JOIN obat o ON d.kode_obat = o.kode_obat
WHERE MONTH(p.tgl_penjualan) = $bulan AND YEAR(p.tgl_penjualan) = $tahun
GROUP BY d.kode_obat
ORDER BY total_terjual DESC
LIMIT 10
";
$result = mysqli_query($conn, $query);

$labels = [];
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['nama_obat'];
    $data[] = $row['total_terjual'];
}
?>

<!-- Wrapper Layout -->
<div class="layout-wrapper">

    <!-- Sidebar -->
    <?php include '../../templates/sidebar_pelayanan.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <div class="px-4 py-4">
            <h2 class="fw-semibold">Beranda</h2>
            <br>
            <div class="card" style="width: 100%; max-width: 1000px;">
                <div class="card-body">
                    <h5 class="card-title">Obat Terlaris</h5>
                    <div style="height: 400px; width: 110%;">
                        <canvas id="chartObatTerlaris"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </main>

</div>
<script>
    const labelObat = <?= json_encode($labels); ?>;
    const dataObat = <?= json_encode($data); ?>;
</script>
<script src="<?= BASE_URL ?>js/chart.js"></script>
<script src="<?= BASE_URL ?>js/active_menu.js"></script>
</body>

</html>