<form method="GET" class="row g-2 mb-3" style="padding-top: 15px;">
    <div class="col-md-1">
        <select name="bulan" id="bulan" class="form-select">
            <option value="">Bulan</option>
            <?php
            foreach ($bulanIndonesia as $num => $namaBulan) {
                $selected = (isset($_GET['bulan']) && $_GET['bulan'] == $num) ? 'selected' : '';
                echo "<option value='$num' $selected>$namaBulan</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-1">
        <select name="tahun" id="tahun" class="form-select">
            <option value="">Tahun</option>
            <?php
            $tahunSekarang = date('Y');
            for ($t = $tahunSekarang; $t >= $tahunSekarang - 5; $t--) {
                $selected = (isset($_GET['tahun']) && $_GET['tahun'] == $t) ? 'selected' : '';
                echo "<option value='$t' $selected>$t</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-2 align-self-end">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="penjualan.php" class="btn btn-secondary">Reset</a>
    </div>
</form>

<div class="table-responsive mt-3">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-light">
            <tr class="text-center">
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Obat</th>
                <th>Jenis</th>
                <th>Jumlah Terjual</th>
                <th>Batch</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center"><?= date('d-m-Y', strtotime($row['tgl_penjualan'])) ?></td>
                    <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                    <td><?= htmlspecialchars($row['jenis']) ?></td>
                    <td class="text-center"><?= $row['jml_terjual'] ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['no_batch']) ?></td>
                    <td class="text-center">
                        <a href="?edit=<?= $row['id_penjualan'] ?>" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <button onclick="confirmDelete('<?= $row['id_detail'] ?>', '<?= $row['id_penjualan'] ?>')"
                            class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </button>

                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>


<script>
    function confirmDelete(idDetail) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data yang dihapus tidak dapat dikembalikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= BASE_URL ?>modules/penjualan/handler/hapus_detail.php?id_detail=" + idDetail;
            }
        });
    }
</script>