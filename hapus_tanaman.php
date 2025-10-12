<?php
include 'config.php';
if (!isset($_SESSION['login'])) header("Location: login.php");

$id = intval($_GET['id']);
$q = mysqli_query($conn, "SELECT * FROM tanaman WHERE id=$id");
$data = mysqli_fetch_assoc($q);

if ($data) {
    // Hapus file gambar dan qrcode
    if (file_exists('uploads/'.$data['gambar'])) unlink('uploads/'.$data['gambar']);
    if (file_exists('qrcodes/'.$data['qrcode'])) unlink('qrcodes/'.$data['qrcode']);

    mysqli_query($conn, "DELETE FROM tanaman WHERE id=$id");
}

header("Location: index.php");
exit;
?>
