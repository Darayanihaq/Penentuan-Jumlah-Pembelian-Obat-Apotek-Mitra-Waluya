<?php
$host = "localhost";
// $username = "root";
// $password = ""; // ubah jika pakai password
// $database = "mitra_waluya";
$username = "u760848756_mitra_waluya";
$password = "@wH15tP=F2x"; // ubah jika pakai password
$database = "u760848756_mitra_waluya";

$conn = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>