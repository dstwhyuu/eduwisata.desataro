<?php
require_once 'config.php';
// Fungsi untuk menangani login via cookie
function loginWithCookie($conn) {
    if (isset($_COOKIE['remember_me'])) {
        list($selector, $validator) = explode(':', $_COOKIE['remember_me']);

        if ($selector && $validator) {
            $sql = "SELECT * FROM auth_tokens WHERE selector = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $selector);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $token = mysqli_fetch_assoc($result);

            if ($token && strtotime($token['expires']) > time()) {
                if (password_verify($validator, $token['hashed_validator'])) {
                    // Validasi berhasil, loginkan user
                    $_SESSION['login'] = true;
                    // Ambil detail user jika perlu, misalnya:
                    // $q_user = mysqli_query($conn, "SELECT * FROM users WHERE id=".$token['user_id']);
                    // $_SESSION['user'] = mysqli_fetch_assoc($q_user);
                    
                    // Penting: Perbarui token untuk keamanan
                    $new_validator = bin2hex(random_bytes(32));
                    $new_hashed_validator = password_hash($new_validator, PASSWORD_DEFAULT);
                    $new_expires = date('Y-m-d H:i:s', time() + 86400 * 30); // 30 hari

                    $update_sql = "UPDATE auth_tokens SET hashed_validator = ?, expires = ? WHERE selector = ?";
                    $update_stmt = mysqli_prepare($conn, $update_sql);
                    mysqli_stmt_bind_param($update_stmt, "sss", $new_hashed_validator, $new_expires, $selector);
                    mysqli_stmt_execute($update_stmt);

                    setcookie('remember_me', $selector . ':' . $new_validator, time() + 86400 * 30, '/');

                    return true;
                }
            }

            // Jika token tidak valid atau kadaluarsa, hapus dari DB dan cookie
            if ($token) {
                $delete_sql = "DELETE FROM auth_tokens WHERE selector = ?";
                $delete_stmt = mysqli_prepare($conn, $delete_sql);
                mysqli_stmt_bind_param($delete_stmt, "s", $selector);
                mysqli_stmt_execute($delete_stmt);
            }
            setcookie('remember_me', '', time() - 3600, '/'); // Hapus cookie dari browser
        }
    }
    return false;
}


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Coba login dengan cookie dulu
if (!isset($_SESSION['login'])) {
    if (loginWithCookie($conn)) {
        header("Location: index.php");
        exit;
    }
}


if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $q = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($q);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['login'] = true;
        // $_SESSION['user'] = $user; // Opsional: simpan data user ke session

        // Logika untuk "Remember Me"
        if (isset($_POST['remember'])) {
            $selector = bin2hex(random_bytes(12));
            $validator = bin2hex(random_bytes(32));
            $hashed_validator = password_hash($validator, PASSWORD_DEFAULT);
            $user_id = $user['id']; // Pastikan ada kolom 'id' di tabel users Anda
            $expires = date('Y-m-d H:i:s', time() + 86400 * 30); // Cookie berlaku 30 hari

            $sql = "INSERT INTO auth_tokens (selector, hashed_validator, user_id, expires) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ssis", $selector, $hashed_validator, $user_id, $expires);
            mysqli_stmt_execute($stmt);

            setcookie('remember_me', $selector . ':' . $validator, time() + 86400 * 30, '/');
        }

        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Quicksand', sans-serif;
      /* GANTI URL GAMBAR DI BAWAH INI */
      background-image: linear-gradient(to right, rgba(167, 160, 174, 0.9), rgba(240, 253, 244, 0.9)), url('images/bg2.jpg');
      background-size: cover;
      background-position: center;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }
    .login-box {
      background: #fff;
      padding: 40px 30px;
      border-radius: 20px;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 450px;
      text-align: center;
      transition: all 0.3s ease;
    }
    .login-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
    }
    h3.mb-4 {
        color: #2e7d32;
        font-weight: 600;
        margin-bottom: 30px !important;
    }
    .form-label {
        font-weight: 600;
        color: #2e7d32;
        margin-bottom: 8px;
        display: block;
    }
    .form-control {
      border-radius: 12px;
      border: 1px solid #ced4da;
      padding: 12px 18px;
      font-size: 1rem;
      transition: all 0.3s ease;
    }
    .form-control:focus {
      border-color: #66bb6a;
      box-shadow: 0 0 0 0.25rem rgba(102, 187, 106, 0.25);
    }
    .btn-success {
      background-color: #43a047;
      border: none;
      border-radius: 12px;
      padding: 12px 25px;
      font-size: 1.1rem;
      font-weight: 600;
      transition: background-color 0.2s ease, transform 0.2s ease;
    }
    .btn-success:hover {
      background-color: #388e3c;
      transform: translateY(-2px);
    }
    .error-msg {
      color: #dc3545;
      margin-top: 15px;
      font-weight: 500;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h3 class="mb-4">Login Admin</h3>
    <form method="post">
      <div class="mb-3 text-start">
        <label for="username" class="form-label">Username</label>
        <input name="username" id="username" class="form-control" placeholder="Masukkan username" required>
      </div>
      <div class="mb-3 text-start">
        <label for="password" class="form-label">Password</label>
        <input name="password" id="password" type="password" class="form-control" placeholder="Masukkan password" required>
      </div>
      
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
            <label class="form-check-label" for="rememberMe" style="font-weight: 500; color: #555;">
                Remember Me
            </label>
        </div>
        <a href="lupa-password.php" style="font-size: 0.9rem; color: #2e7d32; text-decoration: none;">Lupa Password?</a>
      </div>

      <button name="login" class="btn btn-success w-100">Masuk</button>
      
      <?php if (isset($error)): ?>
        <div class="error-msg"><?= $error ?></div>
      <?php endif; ?>
    </form>
  </div>
</body>
</html>