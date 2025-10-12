<?php
// =================================================================
// BAGIAN 1: SEMUA PROSES LOGIKA PHP DILAKUKAN DI SINI DULU
// =================================================================

// Aktifkan pelaporan eror untuk debugging jika diperlukan
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

require_once 'config.php';
session_start();

// Keamanan: Cek login SEBELUM mengirim output HTML apapun
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit; // Perintah berhenti ada di sini. Jika belum login, kode di bawah tidak akan pernah dijalankan.
}

// Inisialisasi variabel untuk pesan feedback
$message = '';
$error_msg = '';

// Jika halaman ini juga memproses formnya sendiri (disarankan)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Siapkan query update yang aman
    $stmt = $conn->prepare("UPDATE settings SET setting_value = ? WHERE setting_name = ?");

    foreach ($_POST as $key => $value) {
        if ($key == 'mail_password' && empty($value)) {
            continue;
        }
        $stmt->bind_param("ss", $value, $key);
        $stmt->execute();
    }
    $stmt->close();
    
    $message = "Pengaturan berhasil disimpan!";
}

// Ambil semua pengaturan dari database untuk ditampilkan di form
$pengaturan = [];
$result = mysqli_query($conn, "SELECT * FROM settings");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $pengaturan[$row['setting_name']] = $row['setting_value'];
    }
} else {
    $error_msg = "Gagal mengambil data pengaturan dari database: " . mysqli_error($conn);
}

// =================================================================
// BAGIAN 2: SETELAH SEMUA LOGIKA SELESAI, BARU MULAI CETAK HTML
// =================================================================
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan Email</title>
    <link href="<?= BASE_URL ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>css/style.css" rel="stylesheet">

    <script src="<?= BASE_URL ?>js/bootstrap.bundle.min.js" defer></script>
</head>
<body>

<?php include 'admin_navigasi.php'; ?>

<div class="container mt-5 py-3">
    <h2>⚙️ Pengaturan Email Pengirim</h2>
    <p class="text-muted">Ubah detail akun email yang digunakan oleh sistem untuk mengirim email (misalnya, untuk reset password).</p>
    <hr>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>
    <?php if (!empty($error_msg)): ?>
        <div class="alert alert-danger"><?= $error_msg ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Nama Pengirim</label>
            <input type="text" name="mail_sender_name" class="form-control" value="<?= htmlspecialchars($pengaturan['mail_sender_name'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Email Pengirim (Username Gmail)</label>
            <input type="email" name="mail_username" class="form-control" value="<?= htmlspecialchars($pengaturan['mail_username'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Password Email (Gunakan Sandi Aplikasi)</label>
            <input type="password" name="mail_password" class="form-control" placeholder="Isi hanya jika ingin mengubah password">
            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah sandi yang sudah tersimpan.</small>
        </div>
        <div class="mb-3">
            <label class="form-label">SMTP Host</label>
            <input type="text" name="mail_host" class="form-control" value="<?= htmlspecialchars($pengaturan['mail_host'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">SMTP Port</label>
            <input type="number" name="mail_port" class="form-control" value="<?= htmlspecialchars($pengaturan['mail_port'] ?? '') ?>">
        </div>
        <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
    </form>
</div>
<script src="<?= BASE_URL ?>js/bootstrap.bundle.min.js"></script>

<script src="<?= BASE_URL ?>js/script.js"></script>

</body>
</html>