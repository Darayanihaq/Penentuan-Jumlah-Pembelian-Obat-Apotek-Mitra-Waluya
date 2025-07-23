<div class="header-form-data text-white px-3 py-2 rounded-top">
    <?= $isEdit ? 'Edit Penerimaan Obat' : 'Tambah Penerimaan Obat' ?>
</div>

<div class="form p-4">
    <form method="POST" action="<?= BASE_URL ?>modules/penerimaan/proses.php">
        <?php if ($isEdit): ?>
            <div class="col-sm-6 col-md-4 col-lg-2">
                <input type="hidden" name="id_penerimaan" value="<?= $dataEdit['id_penerimaan'] ?>">
            </div>
        <?php endif; ?>
        <div class="row g-3">
            <div class="col-sm-6 col-md-6 col-lg-2">
                <label class="form-label">Nama Supplier</label>
                <select name="id_supplier" class="form-select select2" required>
                    <option value="">Pilih Supplier</option>
                    <?php
                    $sup_query = mysqli_query($conn, "SELECT id_supplier, nama_supplier FROM supplier");
                    while ($sup = mysqli_fetch_assoc($sup_query)) {
                        $selected = (isset($id_supplier) && $id_supplier == $sup['id_supplier']) ? 'selected' : '';
                        echo "<option value='{$sup['id_supplier']}' $selected>{$sup['nama_supplier']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3">
                <label class="form-label">Nama Obat</label>
                <select name="kode_obat" class="form-control select2" <?= $dataEdit ? 'disabled' : '' ?> required>
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
            <div class="col-sm-6 col-md-4 col-lg-2">
                <label class="form-label">Tanggal Penerimaan</label>
                <input type="date" name="tgl_penerimaan" class="form-control" required
                    value="<?= $isEdit ? htmlspecialchars($dataEdit['tgl_penerimaan']) : '' ?>">
            </div>
            <div class="col-sm-6 col-md-4 col-lg-2">
                <label class="form-label">Nomor Batch</label>
                <input type="text" name="no_batch" class="form-control" required
                    value="<?= $isEdit ? htmlspecialchars($dataEdit['no_batch']) : '' ?>">
            </div>
            <div class="col-sm-6 col-md-4 col-lg-2">
                <label class="form-label">Tanggal Kedaluwarsa</label>
                <input type="date" name="tgl_kedaluwarsa" class="form-control" required
                    value="<?= $isEdit ? htmlspecialchars($dataEdit['tgl_kedaluwarsa']) : '' ?>">
            </div>
            <div class="col-sm-6 col-md-4 col-lg-1">
                <label class="form-label">Jumlah</label>
                <input type="number" name="jml_masuk" class="form-control" required
                    value="<?= $isEdit ? htmlspecialchars($dataEdit['jml_masuk']) : '' ?>">
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" name="<?= $isEdit ? 'update' : 'tambah' ?>" class="btn btn-primary">
                <i class="bi bi-<?= $isEdit ? 'save' : 'plus' ?>"></i> <?= $isEdit ? 'Update' : 'Tambah' ?>
            </button>
            <a href="penerimaan.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>