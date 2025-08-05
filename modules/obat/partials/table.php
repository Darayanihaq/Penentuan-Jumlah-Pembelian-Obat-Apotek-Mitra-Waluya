<div class="d-flex justify-content-between align-items-center mt-3">
    <div class="search-container">
        <div class="search-box-with-icon">
            <input type="text" id="searchInput" class="search-input" onkeyup="filterTable()" placeholder="Cari...">
        </div>
    </div>
    <div class="filter-container">
        <select id="jenisFilter" class="form-select" onchange="filterTable()">
            <option value="">Semua Jenis Obat</option>
            <?php while ($jenis = mysqli_fetch_assoc($jenisObatList)): ?>
                <option value="<?= htmlspecialchars($jenis['jenis']) ?>">
                    <?= htmlspecialchars($jenis['jenis']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
</div>

<div class="table-responsive mt-3">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-light">
            <tr class="text-center">
                <th>No</th>
                <th>Kode</th>
                <th>Nama Obat</th>
                <th>Satuan</th>
                <th>Jenis</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="obatTableBody">
            <?php
            $no = 1;
            $result = mysqli_query($conn, "SELECT * FROM obat ORDER BY kode_obat ASC");
            while ($row = mysqli_fetch_assoc($result)):
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center"><?= $row['kode_obat'] ?></td>
                    <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                    <td><?= $row['satuan'] ?></td>
                    <td><?= $row['jenis'] ?></td>
                    <td class="text-end">Rp <?= number_format($row['harga_obat']) ?></td>
                    <td class="text-center">
                        <a href="obat.php?edit=<?= $row['kode_obat'] ?>" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    function confirmDelete(id) {
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
                window.location.href = "../../modules/obat/handler/hapus_obat.php?kode_obat=" + id;
            }
        });
    }
</script>