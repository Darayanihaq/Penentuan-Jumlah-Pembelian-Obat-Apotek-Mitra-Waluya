<div class="header-form-data text-white px-3 py-2 rounded-top">
    <?= $isEdit ? 'Edit Penjulan Obat' : 'Tambah Penjualan Obat' ?>
</div>

<div class="form p-4">
    <form method="POST"
        action="<?= $isEdit ? BASE_URL . 'modules/penjualan/handler/ubah_penjualan.php' : BASE_URL . 'modules/penjualan/handler/tambah_penjualan.php' ?>">
        <?php if ($isEdit): ?>
            <div class="col-sm-6 col-md-4 col-lg-2">
                <input type="hidden" name="id_penjualan" value="<?= $dataEdit['id_penjualan'] ?>">
            </div>
        <?php endif; ?>
        <div class="row g-3">
            <div class="col-sm-6 col-md-4 col-lg-2">
                <label class="form-label">Tanggal Penjualan</label>
                <input type="date" name="tgl_penjualan" class="form-control" required
                    value="<?= $isEdit ? htmlspecialchars($dataEdit['tgl_penjualan']) : '' ?>">
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3">
                <label class="form-label">Nama Obat</label>
                <select name="kode_obat" class="form-select select2" <?= $dataEdit ? 'disabled' : '' ?> required>
                    <option value="">Pilih Obat</option>
                    <?php
                    $obat_query = mysqli_query($conn, "SELECT kode_obat, nama_obat, jenis FROM obat");
                    while ($obat = mysqli_fetch_assoc($obat_query)) {
                        $selected = (isset($kode_obat) && $kode_obat == $obat['kode_obat']) ? 'selected' : '';
                        echo "<option value='{$obat['kode_obat']}' $selected>{$obat['nama_obat']} ({$obat['jenis']})</option>";
                    }
                    ?>
                </select>
                <?php if ($dataEdit): ?>
                    <!-- kirim juga id_obat via hidden jika disabled -->
                    <input type="hidden" name="kode_obat" value="<?= $kode_obat ?>">
                <?php endif; ?>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-1">
                <label class="form-label">Jumlah</label>
                <input type="number" name="jml_terjual" class="form-control" required
                    value="<?= $isEdit ? htmlspecialchars($dataEdit['jml_terjual']) : '' ?>">
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" name="<?= $isEdit ? 'update' : 'tambah' ?>" class="btn btn-primary">
                <i class="bi bi-<?= $isEdit ? 'save' : 'plus' ?>"></i> <?= $isEdit ? 'Update' : 'Tambah' ?>
            </button>
            <a href="penjualan.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>