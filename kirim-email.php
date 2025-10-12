<?php
// Gunakan namespace dari PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Panggil autoloader Composer
require_once __DIR__ . '/vendor/autoload.php';

// Cek apakah form sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Di dalam kirim-email.php setelah if ($_SERVER["REQUEST_METHOD"] == "POST")

// Ambil data dan bersihkan dari tag HTML/script berbahaya
$email_penerima = htmlspecialchars($_POST['email_penerima']);
$subjek = htmlspecialchars($_POST['subjek']);
$pesan = htmlspecialchars($_POST['pesan']);

// Validasi sederhana
if (empty($email_penerima) || empty($subjek) || empty($pesan)) {
    die("Error: Semua field harus diisi.");
}

if (!filter_var($email_penerima, FILTER_VALIDATE_EMAIL)) {
    die("Error: Format email tidak valid.");
}

// ... baru lanjutkan ke blok try-catch untuk mengirim email ...
    
    // Ambil data dari form
    $email_penerima = $_POST['email_penerima'];
    $subjek = $_POST['subjek'];
    $pesan = $_POST['pesan'];

    // Buat instance dari PHPMailer
    $mail = new PHPMailer(true);

    try {
        // ----- PENGATURAN SERVER SMTP (Gunakan Akun Gmail Anda) -----
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'destawahyu771@gmail.com';     // <-- GANTI DENGAN ALAMAT EMAIL GMAIL ANDA
        $mail->Password   = 'dnfubdxhhcdkneuv';        // <-- GANTI DENGAN APP PASSWORD ANDA
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // ----- PENGATURAN PENERIMA -----
        $mail->setFrom('emailanda@gmail.com', 'Nama Anda / Nama Website'); // Email dan nama pengirim
        $mail->addAddress($email_penerima);     // Menambahkan penerima dari form
        // $mail->addReplyTo('info@example.com', 'Information'); // Opsional

        // ----- KONTEN EMAIL -----
        $mail->isHTML(true);
        $mail->Subject = $subjek; // Mengambil subjek dari form
        $mail->Body    = nl2br($pesan); // Mengambil pesan dari form, nl2br agar enter tetap terlihat
        $mail->AltBody = $pesan; // Versi text biasa

        $mail->send();
// Ganti bagian ini di kirim-email.php
try {
    // ... (kode SMTP dan pengiriman email Anda) ...

    $mail->send();
    // ALIHKAN KE HALAMAN KONTAK DENGAN STATUS SUKSES
    header("Location: kontak.html?status=sukses");
    exit(); // Penting untuk menghentikan eksekusi skrip setelah redirect

} catch (Exception $e) {
    // ALIHKAN KE HALAMAN KONTAK DENGAN STATUS GAGAL
    header("Location: kontak.html?status=gagal&error=" . urlencode($mail->ErrorInfo));
    exit(); // Penting
}
    } catch (Exception $e) {
        echo "<h3>Pesan gagal dikirim.</h3> Mailer Error: {$mail->ErrorInfo}";
        echo '<br><a href="kontak.html">Kembali ke form</a>';
    }
} else {
    // Jika file diakses langsung tanpa submit form
    echo "Silakan isi formulir terlebih dahulu.";
    echo '<br><a href="kontak.html">Kembali ke form</a>';
}
?>