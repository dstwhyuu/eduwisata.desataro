<?php
// Memanggil file koneksi
require_once 'config.php';

// Memulai session harus dilakukan di awal
session_start();

// Keamanan: Pastikan hanya pengguna yang sudah login yang bisa mengakses
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Inisialisasi variabel pesan
$message = '';

// Proses form hanya jika tombol 'ubah' diklik
if (isset($_POST['ubah'])) {
    $old_username = $_POST['old_username'];
    $old_password = $_POST['old_password'];
    $new_username = $_POST['new_username'];
    $new_password = $_POST['new_password'];

    // Validasi dasar agar tidak ada field yang kosong
    if (empty($old_username) || empty($old_password) || empty($new_username) || empty($new_password)) {
        $message = "<div class='alert alert-danger'>Semua field wajib diisi.</div>";
    } else {
        // PERBAIKAN 1: Gunakan Prepared Statements untuk mengambil data (Aman dari SQL Injection)
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $old_username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        // Cek apakah user ditemukan DAN password lama cocok
        if ($user && password_verify($old_password, $user['password'])) {
            // Hash password baru sebelum disimpan
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

            // PERBAIKAN 2: Gunakan Prepared Statements juga untuk UPDATE (Aman)
            $updateStmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
            $updateStmt->bind_param("ssi", $new_username, $new_password_hash, $user['id']);
            
            if ($updateStmt->execute()) {
                $message = "<div class='alert alert-success'>Username dan password berhasil diperbarui. Anda mungkin perlu login kembali dengan data baru.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Gagal memperbarui data ke database.</div>";
            }
            $updateStmt->close();

        } else {
            $message = "<div class='alert alert-danger'>Username atau password lama salah.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ganti Username & Password</title>
    <link href="<?= BASE_URL ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>css/style.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f0fdf4;
            font-family: 'Poppins', sans-serif;
        }
        .form-box {
            max-width: 500px;
            margin: 60px auto;
            padding: 30px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<?php include 'admin_navigasi.php'; ?>

    <div class="form-box">
        <h4 class="text-center mb-4">🔒 Ganti Username & Password</h4>
        
        <?php if (!empty($message)) echo $message; ?>

        <form method="post">
            <div class="mb-3">
                <label>Username Lama</label>
                <input type="text" name="old_username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password Lama</label>
                <input type="password" name="old_password" class="form-control" required>
            </div>
            <hr>
            <div class="mb-3">
                <label>Username Baru</label>
                <input type="text" name="new_username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password Baru</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="d-grid">
                <button type="submit" name="ubah" class="btn btn-success">Simpan Perubahan</button>
            </div>
            <div class="text-center mt-3">
                <a href="index.php" class="text-decoration-none">← Kembali ke Beranda</a>
            </div>
        </form>
    </div>

    <script src="<?= BASE_URL ?>js/bootstrap.bundle.min.js" defer></script>
    <script src="<?= BASE_URL ?>js/script.js"></script>

</body>
</html>