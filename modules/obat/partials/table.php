<?php
$jenis_obat_query = mysqli_query($conn, "SELECT DISTINCT jenis FROM obat ORDER BY jenis ASC");
?>
<div class="d-flex justify-content-between align-items-center mt-3">
    <div class="search-container">
        <div class="search-box-with-icon">
            <input type="text" id="searchInput" class="search-input" onkeyup="filterTable()" placeholder="Cari...">
        </div>
    </div>
    <div class="filter-container">
        <select id="jenisFilter" class="form-select" onchange="filterTable()">
            <option value="">Semua Jenis Obat</option>
            <?php while ($jenis = mysqli_fetch_assoc($jenis_obat_query)): ?>
                <option value="<?= htmlspecialchars($jenis['jenis']) ?>"><?= htmlspecialchars($jenis['jenis']) ?></option>
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
                    <td class="text-center"><?= $row['satuan'] ?></td>
                    <td class="text-center"><?= $row['jenis'] ?></td>
                    <td class="text-end">Rp <?= number_format($row['harga_obat']) ?></td>
                    <td class="text-center">
                        <a href="obat.php?edit=<?= $row['kode_obat'] ?>" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if (mysqli_num_rows($result) == 0): ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function filterTable() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const jenisFilter = document.getElementById('jenisFilter').value.toLowerCase();
    const tbody = document.getElementById('obatTableBody');
    const rows = tbody.getElementsByTagName('tr');

    for (let row of rows) {
        const cells = row.getElementsByTagName('td');
        if (cells.length === 1) continue; // Skip the "Tidak ada data" row

        const namaObat = cells[2].textContent.toLowerCase();
        const jenisObat = cells[4].textContent.toLowerCase();
        const kodeObat = cells[1].textContent.toLowerCase();

        const matchSearch = namaObat.includes(searchInput) || 
                           kodeObat.includes(searchInput);
        const matchJenis = jenisFilter === '' || jenisObat === jenisFilter;

        row.style.display = (matchSearch && matchJenis) ? '' : 'none';
    }
}
</script>