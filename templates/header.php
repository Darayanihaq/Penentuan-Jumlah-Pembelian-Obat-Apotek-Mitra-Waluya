<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';

if (!isset($_SESSION['role']) || !isset($_SESSION['nama_user'])) {
    header("Location: ../pages/login.php");
    exit;
}

$nama = $_SESSION['nama_user'];
$role = ucfirst($_SESSION['role']); // admin → Admin
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sistem Informasi Apotek</title>

    <!-- <Bootsrap CSS> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- <Font Google dan Ikon> -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.2.2/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>

<body>
    <header class="main-header d-flex justify-content-between align-items-center px-3 py-3 bg-white shadow-sm">
        <div class="d-flex align-items-center gap-2">
            <img src="<?= BASE_URL ?>asset/logo.jpg" alt="Logo" width="70" height="45" />
            <span class="fw-semibold">APOTEK MITRA WALUYA</span>
        </div>
        <div class="d-flex gap-3">
            <div class="admin-profile d-flex align-items-center">
                <img src="<?= BASE_URL ?>asset/profile.png" alt="Avatar" class="admin-avatar me-2">
                <div>
                    <div class="admin-greeting">Hi <strong><?= $nama ?>!</strong></div>
                    <div class="admin-role"><?= $role ?></div>
                </div>
                <div class="d-flex gap-1">
                    <a href="<?= BASE_URL ?>pages/logout.php" class="btn btn-link text-dark">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>