<div class="header-form-data text-white px-3 py-2 rounded-top">
    <?= $isEdit ? 'Edit Data Obat' : 'Tambah Data Obat' ?>
</div>
<div class="form p-4">
    <form method="POST" action="<?= BASE_URL ?>modules/obat/proses.php">
        <?php if ($isEdit): ?>
            <input type="hidden" name="kode_obat" value="<?= $dataEdit['kode_obat'] ?>">
        <?php endif; ?>

        <div class="row g-3">
            <div class="col-sm-6 col-md-4 col-lg-3">
                <label class="form-label">Nama Obat</label>
                <input type="text" name="nama_obat" class="form-control" required
                    value="<?= $isEdit ? htmlspecialchars($dataEdit['nama_obat']) : '' ?>">
            </div>

            <div class="col-sm-6 col-md-3 col-lg-2">
                <label class="form-label">Satuan</label>
                <select name="satuan" class="form-select" required>
                    <option value="">-- Pilih Jenis --</option>
                    <?php
                    $satuanOptions = ['Strip', 'Botol', 'Tube'];
                    foreach ($satuanOptions as $opt):
                        $selected = $isEdit && $dataEdit['satuan'] === $opt ? 'selected' : '';
                        echo "<option value=\"$opt\" $selected>$opt</option>";
                    endforeach;
                    ?>
                </select>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <label class="form-label">Jenis Obat</label>
                <select name="jenis" class="form-select" required>
                    <option value="">-- Pilih Jenis --</option>
                    <?php
                    $jenisOptions = ['Tablet Generik', 'Tablet Paten', 'Sirup', 'Vitamin', 'Salep'];
                    foreach ($jenisOptions as $opt):
                        $selected = $isEdit && $dataEdit['jenis'] === $opt ? 'selected' : '';
                        echo "<option value=\"$opt\" $selected>$opt</option>";
                    endforeach;
                    ?>
                </select>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-2">
                <label class="form-label">Harga Obat</label>
                <input type="number" name="harga_obat" class="form-control" required
                    value="<?= $isEdit ? htmlspecialchars($dataEdit['harga_obat']) : '' ?>">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" name="<?= $isEdit ? 'update' : 'tambah' ?>" class="btn btn-primary">
                <i class="bi bi-<?= $isEdit ? 'save' : 'plus' ?>"></i> <?= $isEdit ? 'Update' : 'Tambah' ?>
            </button>
            <a href="obat.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>