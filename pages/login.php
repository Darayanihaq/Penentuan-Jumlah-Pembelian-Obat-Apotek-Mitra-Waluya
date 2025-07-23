<?php
session_start();

// Load konfigurasi dan koneksi
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';
include __DIR__ . '/../components/alert.php';

// Jika sudah login, arahkan langsung ke dashboard sesuai role
if (isset($_SESSION['username']) && isset($_SESSION['role'])) {
    $redirectRole = strtolower($_SESSION['role']);
    header("Location: " . BASE_URL . "pages/{$redirectRole}/dashboard_{$redirectRole}.php");
    exit;
}

// Proses login saat form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $_SESSION['alert'] = [
            'type' => 'warning',
            'message' => 'Harap isi username dan password.'
        ];
    } else {
        $username = mysqli_real_escape_string($conn, $username);
        $query = mysqli_query($conn, "SELECT * FROM pengguna WHERE username='$username'");
        $user = mysqli_fetch_assoc($query);

        if ($user && $password === $user['password']) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = strtolower($user['role']);
            $_SESSION['nama_user'] = $user['nama_user'];

            // ⬇ Tambahkan baris ini agar $id_user bisa diakses di penjualan
            $_SESSION['user'] = [
                'id_user' => $user['id_user'],
                'username' => $user['username'],
                'role' => strtolower($user['role']),
                'nama_user' => $user['nama_user']
            ];

            $redirectRole = strtolower($user['role']);
            header("Location: " . BASE_URL . "pages/{$redirectRole}/dashboard_{$redirectRole}.php");
            exit;
        } else {
            $_SESSION['alert'] = [
                'type' => 'error',
                'message' => 'Username atau password salah.'
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Sistem Informasi Apotek</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css" />
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="login-container bg-white text-center shadow p-5 rounded" style="max-width: 400px; width: 100%;">

            <div class="login-icon mb-4">
                <img src="<?= BASE_URL ?>asset/logo.jpg" alt="Logo" width="150" height="70" />
            </div>

            <h4 class="mb-4">Login</h4>

            <?php include __DIR__ . '/../components/alert.php'; ?>

            <form method="POST" action="">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="username" id="username" placeholder="Username"
                        required>
                    <label for="username">Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password"
                        required>
                    <label for="password">Password</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Masuk</button>
            </form>
        </div>
    </div>
</body>

</html>