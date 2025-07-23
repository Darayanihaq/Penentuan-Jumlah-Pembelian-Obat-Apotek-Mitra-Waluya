<div class="table-responsive mt-3">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-light">
            <tr class="text-center">
                <th>No</th>
                <th>Obat</th>
                <th>Jenis</th>
                <th>Supplier</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            echo "<pre>";
            echo "</pre>";

            if ($result && mysqli_num_rows($result) > 0):
                while ($row = mysqli_fetch_assoc($result)):
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                        <td><?= htmlspecialchars($row['jenis']) ?></td>
                        <td><?= htmlspecialchars($row['nama_supplier']) ?></td>
                        <td class="text-center"><?= $row['jml_pembelian'] ?></td>
                        <td class="text-end">Rp <?= number_format($row['jml_pembelian'] * $row['harga_obat'], 0, ',', '.') ?>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalDetailPembelian"
                                data-id="<?= $row['id_pembelian'] ?>" data-kode-obat="<?= $row['kode_obat'] ?>"
                                data-nama-obat="<?= $row['nama_obat'] ?>" data-jumlah="<?= $row['jml_pembelian'] ?>"
                                data-bulan="<?= $row['bulan_peramalan'] ?>" data-hasil="<?= $row['hasil_peramalan'] ?>"
                                data-mad="<?= $row['mad_peramalan'] ?>" data-mape="<?= $row['mape_peramalan'] ?>">
                                <i class="bi bi-eye"></i> Detail
                            </button>
                            <button onclick="confirmDelete('<?= $row['id_peramalan'] ?>', '<?= $row['id_pembelian'] ?>')"
                                class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php
                endwhile;
            else:
                ?>
                <tr>
                    <td colspan="8" class="text-center text-muted">Tidak ada data pembelian</td>
                </tr>
                <?php
            endif;
            ?>
        </tbody>
    </table>
</div>


<script>
    function confirmDelete(idPeramalan, idPembelian) {
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
                window.location.href = "<?= BASE_URL ?>modules/pembelian/handler/hapus_pembelian.php?id_peramalan=" + idPeramalan + "&id_pembelian=" + idPembelian;
            }
        });
    }

</script>