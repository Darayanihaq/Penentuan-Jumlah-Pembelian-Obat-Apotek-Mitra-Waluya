<?php
session_start();

// Jika belum login, arahkan ke login
if (!isset($_SESSION['role'])) {
    header("Location: pages/login.php");
    exit;
}

// Arahkan ke dashboard sesuai role
switch (strtolower($_SESSION['role'])) {
    case 'administrator':
        header("Location: pages/administrator/dashboard_administrator.php");
        break;
    case 'apoteker':
        header("Location: pages/dashboard_apoteker.php");
        break;
    case 'pengadaan':
        header("Location: pages/pengadaan/dashboard_pengadaan.php");
        break;
    case 'pelayanan':
        header("Location: pages/dashboard_pelayanan.php");
        break;
    default:
        echo "Role tidak dikenali.";
        session_destroy();
        exit;
}
?>