<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['role'])) {
    header("Location: " . BASE_URL . "pages/login.php");
    exit;
}


// Fungsi untuk membatasi akses per role
function onlyAdmin()
{
    if ($_SESSION['role'] !== 'administrator') {
        header("Location: " . BASE_URL . "pages/login.php");
        exit;
    }
}

function onlyApoteker()
{
    if ($_SESSION['role'] !== 'apoteker') {
        header("Location: ../pages/login.php");
        exit;
    }
}

function onlyPengadaan()
{
    if ($_SESSION['role'] !== 'pengadaan') {
        header("Location: ../pages/login.php");
        exit;
    }
}

function onlyPelayanan()
{
    if ($_SESSION['role'] !== 'pelayanan') {
        header("Location: ../pages/login.php");
        exit;
    }
}
?>