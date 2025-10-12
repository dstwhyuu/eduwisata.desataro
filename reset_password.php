<?php
// FILE RESET_PASSWORD VERSI FINAL (DENGAN PERBAIKAN TIMEZONE)

// Menetapkan zona waktu di paling atas adalah praktik terbaik.
date_default_timezone_set('Asia/Makassar'); // WITA

require_once 'config.php';
session_start();

// --- Inisialisasi Variabel ---
$token = $_GET['token'] ?? null;
$error = '';
$message = '';
$showForm = false;

// --- Proses Utama ---

if (isset($_POST['update-password'])) {
    // Proses setelah pengguna submit password baru
    $post_token = $_POST['token'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if (empty($password) || empty($password_confirm)) {
        $error = "Password baru dan konfirmasi password tidak boleh kosong.";
        $showForm = true;
    } elseif ($password !== $password_confirm) {
        $error = "Konfirmasi password tidak cocok.";
        $showForm = true;
    } else {
        $new_password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires_at = NULL WHERE reset_token = ?");
        $stmt->bind_param("ss", $new_password_hash, $post_token);
        
        if ($stmt->execute()) {
            $message = "Password Anda berhasil direset! Silakan <a href='login.php' class='alert-link'>login</a>.";
        } else {
            $error = "Terjadi kesalahan saat memperbarui password.";
            $showForm = true;
        }
        $stmt->close();
    }

} else if ($token) {
    // Proses saat halaman pertama kali dibuka dari link email

    // === PERBAIKAN KUNCI ADA DI SINI ===
    // Daripada membandingkan dengan NOW() milik database, kita bandingkan dengan waktu dari PHP
    // yang sudah pasti menggunakan timezone yang benar.
    $current_time = date('Y-m-d H:i:s'); 

    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expires_at > ?");
    $stmt->bind_param("ss", $token, $current_time); // Menggunakan variabel waktu dari PHP
    // ===================================
    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $showForm = true;
    } else {
        $error = "Link reset password tidak valid atau sudah kedaluwarsa.";
    }
    $stmt->close();

} else {
    $error = "Token tidak ditemukan. Link tidak valid.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Atur Password Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Quicksand', sans-serif; background: #f0fdf4; display: flex; align-items: center; min-height: 100vh; }
        .reset-box { background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.1); width: 100%; max-width: 500px; margin: auto; }
    </style>
</head>
<body>
  <div class="reset-box">
    <h3 class="text-center mb-4">Atur Password Baru</h3>
    
    <?php if ($message): ?><div class="alert alert-success"><?= $message ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <?php if ($showForm): ?>
    <form method="post" action="reset_password.php?token=<?= htmlspecialchars($token) ?>">
      <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
      <div class="mb-3">
        <label for="password" class="form-label">Password Baru</label>
        <input type="password" name="password" id="password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="password_confirm" class="form-label">Konfirmasi Password Baru</label>
        <input type="password" name="password_confirm" id="password_confirm" class="form-control" required>
      </div>
      <button type="submit" name="update-password" class="btn btn-success w-100 mt-3">Simpan Password Baru</button>
    </form>
    <?php endif; ?>

    <?php if (!$showForm && empty($message)): ?>
        <div class="text-center mt-3">
            <a href="lupa-password.php">Minta link baru</a> atau <a href="login.php">Kembali ke Login</a>
        </div>
    <?php endif; ?>
  </div>
</body>
</html>