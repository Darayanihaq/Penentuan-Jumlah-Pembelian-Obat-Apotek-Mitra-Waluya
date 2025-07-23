<!-- Tombol Toggle Sidebar (hanya muncul di layar kecil) -->
<button class="btn btn-outline-secondary d-md-none m-3" type="button" data-bs-toggle="collapse"
    data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <i class="bi bi-list"></i> Menu
</button>

<aside id="sidebarMenu" class="bg-white shadow-sm sidebar-fixed">
    <nav class="nav flex-column gap-2">
        <ul class="nav flex-column">

            <!-- BERANDA -->
            <li class="nav-item">
                <a href="<?= BASE_URL ?>pages/pengadaan/dashboard_pengadaan.php"
                    class="nav-link text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-grid fs-5"></i>
                    <span>Beranda</span>
                </a>
            </li>

            <!-- KELOLA DATA -->
            <li class="nav-item mt-1">
                <a class="nav-link d-flex justify-content-between align-items-center text-dark"
                    data-bs-toggle="collapse" href="#transaksiData" role="button" aria-expanded="false"
                    aria-controls="transaksiData">
                    <span><i class="bi bi-cash-stack fs-6 me-2"></i>Transaksi</span>
                    <i class="fas fa-chevron-down"></i>
                </a>
                <div class="collapse ps-3 mt-1" id="transaksiData">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="<?= BASE_URL ?>pages/pengadaan/penerimaan.php"
                                class="nav-link text-dark d-flex align-items-center gap-2">
                                <i class="bi bi-truck fs-5"></i>
                                <span>Penerimaan Obat</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a href="<?= BASE_URL ?>pages/pengadaan/stok_obat.php"
                    class="nav-link text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-capsule-pill fs-5"></i>
                    <span>Stok Obat</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= BASE_URL ?>pages/pengadaan/pembelian.php"
                    class="nav-link text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-cart2 fs-5"></i>
                    <span>Pembelian</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>