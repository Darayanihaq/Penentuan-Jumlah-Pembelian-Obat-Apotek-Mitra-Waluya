<div class="col-md-4">
    <div class="card shadow-sm border-0 h-100">
        <div class="px-3 py-3 text-white fw-semibold rounded-top" style="background-color: #0e9cff;">
            <h5 class="mb-0 mt-1">Data Penjualan Obat Terakhir</h5>
        </div>
        <div class="card-body pt-1 overflow-auto">
            <ul class="list-group list-group-flush">
                <?php if (mysqli_num_rows($penjualanTerakhir) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($penjualanTerakhir)): ?>
                        <li class="list-group-item py-3" style="transition: background-color 0.2s;">
                            <div class="fw-semibold">
                                <i class="bi bi-capsule me-2 text-primary"></i>
                                <?= htmlspecialchars($row['nama_obat']) ?>
                            </div>
                            <div class="small text-muted">
                                <?= $row['jml_terjual'] ?> terjual • <?= date('d M Y', strtotime($row['tgl_penjualan'])) ?>
                            </div>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li class="list-group-item text-muted">Tidak ada data penjualan</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>