<div class="search-container mt-3">
    <div class="search-box-with-icon">
        <input type="text" id="searchInput" class="search-input"
            onkeyup="searchTable('searchInput', 'supplierTableBody')" placeholder="Cari...">
    </div>
</div>

<div class="table-responsive mt-3">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-light">
            <tr class="text-center">
                <th>No</th>
                <th>Kode</th>
                <th>Nama Supplier</th>
                <th>Alamat</th>
                <th>No. Kontak</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="supplierTableBody">
            <?php
            $no = 1;
            $result = mysqli_query($conn, "SELECT * FROM supplier");
            while ($row = mysqli_fetch_assoc($result)):
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center"><?= $row['id_supplier'] ?></td>
                    <td><?= htmlspecialchars($row['nama_supplier']) ?></td>
                    <td><?= $row['alamat'] ?></td>
                    <td class="text-center"><?= $row['no_kontak'] ?></td>
                    <td class="text-center">
                        <a href="supplier.php?edit=<?= $row['id_supplier'] ?>" class='btn btn-warning btn-sm'>
                            <i class="bi bi-pencil-square"></i>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if (mysqli_num_rows($result) == 0): ?>
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data supplier</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>