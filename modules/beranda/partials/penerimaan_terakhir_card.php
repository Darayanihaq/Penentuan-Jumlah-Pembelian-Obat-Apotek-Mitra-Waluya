<div class="col-md-12 h-100">
    <div class="card shadow-sm border-0 d-flex flex-column h-100" style="min-height: 515px;">
        <div class="px-4 py-3 text-dark rounded-top">
            <h5 class="mt-1 mb-0 text-dark fw-semibold">Data Penerimaan Obat Terakhir</h5>
        </div>
        <div class="card-body pt-1 flex-grow-1 overflow-auto">
            <ul class="list-group list-group-flush">
                <?php if (mysqli_num_rows($penerimaan) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($penerimaan)): ?>
                        <li class="list-group-item">
                            <div class="fw-semibold"><?= htmlspecialchars($row['nama_obat']) ?></div>
                            <div class="small text-muted">
                                <?= $row['jml_masuk'] ?> diterima dari <?= htmlspecialchars($row['nama_supplier']) ?> •
                                <?= date('d M Y', strtotime($row['tgl_penerimaan'])) ?>
                            </div>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li class="list-group-item text-muted">Tidak ada data penerimaan</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>