<?php
$host = "localhost";
$username = "root";
$password = ""; // ubah jika pakai password
$database = "mitra_waluya";

$conn = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>