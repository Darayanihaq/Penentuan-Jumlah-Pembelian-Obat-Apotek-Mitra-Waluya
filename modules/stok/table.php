<?php
require_once __DIR__ . '/query.php';
?>
<div class="table-responsive mt-3">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-light">
            <tr class="text-center">
                <th>No</th>
                <th>No Batch</th>
                <th>Nama Obat</th>
                <th>Jenis</th>
                <th>Tanggal Kedaluwarsa</th>
                <th>Jumlah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="stokTableBody">
            <?php
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)):
                $stok = $row['jml_stok_saat_ini'];
                $tgl_kedaluwarsa = strtotime($row['tgl_kedaluwarsa']);
                $hari_ini = strtotime(date('Y-m-d'));
                $selisih_hari = ($tgl_kedaluwarsa - $hari_ini) / (60 * 60 * 24);

                if ($stok <= 0) {
                    $status = '<span class="badge bg-danger">Kosong</span>';
                } elseif ($stok <= 10) {
                    $status = '<span class="badge bg-warning text-dark">Hampir Habis</span>';
                } elseif ($selisih_hari <= 30) {
                    $status = '<span class="badge bg-warning text-dark"> Kedaluwarsa</span>';
                } else {
                    $status = '<span class="badge bg-success">Tersedia</span>';
                }
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['no_batch']) ?></td>
                    <td><?= htmlspecialchars($row['nama_obat']) ?></td>
                    <td><?= htmlspecialchars($row['jenis']) ?></td>
                    <td class="text-center"><?= date('d-m-Y', strtotime($row['tgl_kedaluwarsa'])) ?></td>
                    <td class="text-center"><?= htmlspecialchars($row['jml_stok_saat_ini']) ?></td>
                    <td class="text-center"><?= $status ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>