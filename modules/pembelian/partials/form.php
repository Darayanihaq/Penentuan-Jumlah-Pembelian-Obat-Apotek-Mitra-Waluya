<?php
// Ambil data obat dan supplier
$obat_query = mysqli_query($conn, "SELECT kode_obat, nama_obat, jenis FROM obat");
$supplier_query = mysqli_query($conn, "SELECT id_supplier, nama_supplier FROM supplier");

// Buat array bulan
$bulan_list = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
];
?>

<div class="header-form-data text-white px-3 py-2 rounded-top">
    <?= 'Tambah Pembelian Obat' ?>
</div>
<div class="form p-4">
    <form method="POST" action="<?= BASE_URL ?>modules/pembelian/handler/tambah_pembelian.php">
        <div class="row g-3">
            <!-- Pilih Obat -->
            <div class="col-md-3">
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
            </div>

            <!-- Pilih Supplier -->
            <div class="col-md-3">
                <label class="form-label">Supplier</label>
                <select name="id_supplier" class="form-select select2" required>
                    <option value="">Pilih Supplier</option>
                    <?php while ($supplier = mysqli_fetch_assoc($supplier_query)): ?>
                        <option value="<?= $supplier['id_supplier'] ?>">
                            <?= $supplier['nama_supplier'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Pilih Bulan Pembelian -->
            <div class="col-md-2">
                <label class="form-label">Bulan Pembelian</label>
                <select name="bulan_pembelian" class="form-select" required>
                    <option value="">Pilih Bulan</option>
                    <?php
                    $bulan_sekarang = date('n');
                    foreach ($bulan_list as $num => $nama):
                        $selected = ($num == $bulan_sekarang) ? 'selected' : '';
                        echo "<option value='$num' $selected>$nama</option>";
                    endforeach;
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Tahun Pembelian</label>
                <select name="tahun_pembelian" class="form-select" required>
                    <option value="">Pilih Tahun</option>
                    <?php
                    $tahun_sekarang = date('Y');
                    for ($i = $tahun_sekarang - 5; $i <= $tahun_sekarang + 5; $i++):
                        $selected = ($i == $tahun_sekarang) ? 'selected' : '';
                        echo "<option value='$i' $selected>$i</option>";
                    endfor;
                    ?>
                </select>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Tambah
                </button>
                <a href="pembelian.php" class="btn btn-secondary">Batal</a>

            </div>
        </div>
</div>
</form>