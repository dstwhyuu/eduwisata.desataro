<?php

define('BASE_URL', 'http://localhost/Sistem_Informasi_Eduwisata_Taro/');

$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_tanaman";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// ATURAN PALING PENTING:
// JANGAN ADA APA-APA LAGI SETELAH INI.
// JANGAN ADA SPASI, JANGAN ADA BARIS KOSONG.
// JANGAN ADA TAG PENUTUP PHP
