<div class="header-form-data text-white px-3 py-2 rounded-top">
    <?= $isEdit ? 'Edit Data Supplier' : 'Tambah Data Supplier' ?>
</div>

<div class="form p-4">
    <form method="POST" action="<?= BASE_URL ?>modules/supplier/proses.php">
        <?php if ($isEdit): ?>
            <div class="col-sm-6 col-md-4 col-lg-2">
                <input type="hidden" name="id_supplier" value="<?= $dataEdit['id_supplier'] ?>">
            </div>
        <?php endif; ?>

        <div class="row g-3">
            <div class="col-sm-6 col-md-4 col-lg-3">
                <label class="form-label">Supplier</label>
                <input type="text" name="nama_supplier" class="form-control" required
                    value="<?= $isEdit ? htmlspecialchars($dataEdit['nama_supplier']) : '' ?>">
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control"
                    required><?= $isEdit ? htmlspecialchars($dataEdit['alamat']) : '' ?></textarea>
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <label class="form-label">No. Kontak</label>
                <input type="text" name="no_kontak" class="form-control" required
                    value="<?= $isEdit ? htmlspecialchars($dataEdit['no_kontak']) : '' ?>">
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" name="<?= $isEdit ? 'update' : 'tambah' ?>" class="btn btn-primary">
                <i class="bi bi-<?= $isEdit ? 'save' : 'plus' ?>"></i> <?= $isEdit ? 'Update' : 'Tambah' ?>
            </button>
            <a href="supplier.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>