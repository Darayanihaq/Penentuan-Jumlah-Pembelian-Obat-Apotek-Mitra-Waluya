<div class="table-responsive mt-3">
    <table class="table table-bordered table-hover table-striped">
        <thead class="table-light">
            <tr class="text-center">
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Password</th>
                <th>Peran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="penggunaTableBody">
            <?php
            $no = 1;
            $result = mysqli_query($conn, "SELECT * FROM pengguna");
            while ($row = mysqli_fetch_assoc($result)):
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center"><?= $row['id_user'] ?></td>
                    <td><?= htmlspecialchars($row['nama_user']) ?></td>
                    <td><?= $row['username'] ?></td>
                    <td><?= $row['password'] ?></td>
                    <td><?= $row['role'] ?></td>
                    <td class="text-center">
                        <a href="pengguna.php?edit=<?= $row['id_user'] ?>" class='btn btn-warning btn-sm'>
                            <i class="bi bi-pencil-square"></i>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>