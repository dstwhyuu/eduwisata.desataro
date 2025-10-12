<?php
// FILE LUPA-PASSWORD VERSI FINAL (PENGATURAN DIAMBIL DARI DATABASE)

// Atur zona waktu agar konsisten
date_default_timezone_set('Asia/Makassar');

// Memanggil semua library yang dibutuhkan
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'config.php'; 
require_once __DIR__ . '/vendor/autoload.php';

// Memulai session jika belum ada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Jika sudah login, lempar ke halaman utama
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

// Variabel untuk menampung pesan
$message = '';
$error = '';

// Proses hanya jika form disubmit
if (isset($_POST['reset-password'])) {
    $username = $_POST['username'];

    // Gunakan Prepared Statements untuk keamanan
    $stmt = $conn->prepare("SELECT id, username, email FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // --- INI BAGIAN BARU: Ambil Pengaturan Email dari Database ---
        $pengaturan_email = [];
        $settings_result = mysqli_query($conn, "SELECT setting_name, setting_value FROM settings WHERE setting_name LIKE 'mail_%'");
        while ($row = mysqli_fetch_assoc($settings_result)) {
            $pengaturan_email[$row['setting_name']] = $row['setting_value'];
        }
        // -----------------------------------------------------------

        $token = bin2hex(random_bytes(32));
        $expires_at = date('Y-m-d H:i:s', strtotime('+3 hours')); // Dibuat 3 jam untuk kenyamanan

        $updateStmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expires_at = ? WHERE id = ?");
        $updateStmt->bind_param("ssi", $token, $expires_at, $user['id']);
        $updateStmt->execute();

        $resetLink = "http://tanaman_taro.test/reset_password.php?token=" . $token;

        $mail = new PHPMailer(true);
        try {
            // --- INI BAGIAN YANG DIUBAH: Konfigurasi dari Database ---
            $mail->isSMTP();
            $mail->Host       = $pengaturan_email['mail_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $pengaturan_email['mail_username'];
            $mail->Password   = $pengaturan_email['mail_password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = (int)$pengaturan_email['mail_port']; // Konversi port ke integer

            // Pengirim
            $mail->setFrom($pengaturan_email['mail_username'], $pengaturan_email['mail_sender_name']);
            // Penerima
            $mail->addAddress($user['email'], $user['username']); 

            // Konten Email
            $mail->isHTML(true);
            $mail->Subject = 'Link Reset Password Anda';
            $mail->Body    = "Halo " . $user['username'] . ",<br><br>Klik link berikut untuk mereset password Anda: <a href='" . $resetLink . "'>" . $resetLink . "</a><br><br>Link ini hanya berlaku selama 3 jam.";
            $mail->AltBody = 'Salin dan tempel link berikut di browser Anda untuk mereset password: ' . $resetLink;

            $mail->send();
            $message = 'Link reset password telah dikirim ke email Anda. Silakan periksa inbox atau folder spam.';
        } catch (Exception $e) {
            $error = "Gagal mengirim email. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $message = "Jika akun Anda terdaftar, instruksi reset password akan dikirimkan ke email Anda.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Lupa Password</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Quicksand', sans-serif; background: #f0fdf4; }
    .reset-box { background: #fff; padding: 40px 30px; border-radius: 20px; box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1); width: 100%; max-width: 500px; text-align: center; margin: 60px auto; }
    h3 { color: #2e7d32; font-weight: 600; }
    .btn-success { background-color: #43a047; border: none; }
    .btn-success:hover { background-color: #388e3c; }
  </style>
</head>
<body>
  <div class="reset-box">
    <h3 class="mb-4">Reset Password</h3>
    <p class="text-muted mb-4">Masukkan username Anda. Kami akan mengirimkan link untuk mengatur ulang password Anda.</p>
    
    <?php if ($message): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="mb-3 text-start">
        <label for="username" class="form-label">Username</label>
        <input name="username" id="username" class="form-control" placeholder="Masukkan username Anda" required>
      </div>
      <button type="submit" name="reset-password" class="btn btn-success w-100 mt-3">Kirim Link Reset</button>
      <div class="mt-4">
        <a href="login.php">Kembali ke Login</a>
      </div>
    </form>
  </div>
</body>
</html>