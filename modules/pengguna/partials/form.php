<div class="header-form-data text-white px-3 py-2 rounded-top">
    <?= $isEdit ? 'Edit Data User' : 'Tambah Data User' ?>
</div>

<div class="form p-4">
    <form method="POST" action="<?= BASE_URL ?>modules/pengguna/proses.php">
        <?php if ($isEdit): ?>
            <div class="col-sm-6 col-md-4 col-lg-2">
                <input type="hidden" name="id_user" value="<?= $dataEdit['id_user'] ?>">
            </div>
        <?php endif; ?>
        <div class="row g-3">
            <div class="col-sm-6 col-md-4 col-lg-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama_user" class="form-control" required
                    value="<?= $isEdit ? htmlspecialchars($dataEdit['nama_user']) : '' ?>">
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required
                    value="<?= $isEdit ? htmlspecialchars($dataEdit['username']) : '' ?>">
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <label class="form-label">Password</label>
                <input type="text" name="password" class="form-control" required
                    value="<?= $isEdit ? htmlspecialchars($dataEdit['password']) : '' ?>">
            </div>

            <div class="col-sm-6 col-md-4 col-lg-3">
                <label class="form-label">Peran</label>
                <input type="text" name="role" class="form-control" required
                    value="<?= $isEdit ? htmlspecialchars($dataEdit['role']) : '' ?>">
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" name="<?= $isEdit ? 'update' : 'tambah' ?>" class="btn btn-primary">
                <i class="bi bi-<?= $isEdit ? 'save' : 'plus' ?>"></i> <?= $isEdit ? 'Update' : 'Tambah' ?>
            </button>
            <a href="pengguna.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>