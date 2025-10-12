<?php
require_once 'config.php';
if (!isset($_SESSION['login'])) header("Location: login.php");
exit;
$id = intval($_GET['id']);
$q = mysqli_query($conn, "SELECT * FROM tanaman WHERE id=$id");
$data = mysqli_fetch_assoc($q);
if (!$data) { echo "Data tidak ditemukan."; exit; }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Print QR Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    @media print {
        .btn { display: none; }
    }
    </style>
</head>
<body onload="window.print()">
<div class="container mt-4">
    <div class="card text-center shadow-sm">
        <div class="card-header bg-success text-white">
            <h5><strong><?= $data['nama'] ?></strong></h5>
        </div>
        <div class="card-body">
            <img src="qrcodes/<?= htmlspecialchars($data['qrcode']) ?>" width="200" class="img-thumbnail"><br>            
            <a href="index.php" class="btn btn-secondary mt-3">Kembali</a>
        </div>
    </div>
</div>
</body>
</html>
