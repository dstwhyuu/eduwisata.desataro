<?php
// FILE TAMBAH_TANAMAN VERSI FINAL (DENGAN INPUT VIDEO YOUTUBE)

require_once 'config.php';
if (session_status() == PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit; }

require_once __DIR__ . '/vendor/autoload.php';
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

// Ambil data kategori untuk dropdown
$kategori_q = mysqli_query($conn, "SELECT * FROM kategori");

if (isset($_POST['simpan'])) {
    // 1. Ambil semua data dari form, termasuk video_youtube
    $nama = $_POST['nama'] ?? null;
    $kategori = $_POST['kategori'] ?? null;
    $deskripsi = $_POST['deskripsi'] ?? null;
    $deskripsi_en = $_POST['deskripsi_en']; // Asumsikan ini ada di form Anda
    $link_referensi = $_POST['link_referensi'] ?? null;
    $video_youtube = $_POST['video_youtube'] ?? null; // <-- DATA VIDEO DIAMBIL

    // Proses Upload Gambar
    $namaFileUnik = '';
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $gambarName = $_FILES['gambar']['name'];
        $gambarTmp = $_FILES['gambar']['tmp_name'];
        $namaFileUnik = uniqid() . '-' . strtolower(str_replace(' ', '-', $gambarName));
        move_uploaded_file($gambarTmp, 'uploads/' . $namaFileUnik);
    }

    // Simpan data ke database, termasuk kolom video_youtube
    $stmt = $conn->prepare(
        "INSERT INTO tanaman (nama, kategori, deskripsi, deskripsi_en, link_referensi, video_youtube, gambar, qrcode) 
         VALUES (?, ?, ?, ?, ?, ?, ?, '')"
    );
    // 's' = string. Ada 7 data string sekarang.
    $stmt->bind_param("sssssss", 
        $nama, $kategori, $deskripsi, $deskripsi_en, $link_referensi, $video_youtube, $namaFileUnik
    );
    $stmt->execute();
    $last_id = $stmt->insert_id;
    $stmt->close();

    // Generate & Update QR Code
    $writer = new PngWriter();
    $qrCode = QrCode::create(BASE_URL . "detail_tanaman.php?id=" . $last_id)->setSize(300)->setMargin(10);
    $resultQR = $writer->write($qrCode);
    $qrcode_name = 'qrcode-' . $last_id . '.png';
    $resultQR->saveToFile('qrcodes/' . $qrcode_name);

    $update_stmt = $conn->prepare("UPDATE tanaman SET qrcode = ? WHERE id = ?");
    $update_stmt->bind_param("si", $qrcode_name, $last_id);
    $update_stmt->execute();
    $update_stmt->close();

    header("Location: index.php?status=sukses_tambah");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Tanaman</title>
    <link href="<?= BASE_URL ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>css/style.css" rel="stylesheet">
    <script src="<?= BASE_URL ?>ckeditor/ckeditor.js"></script>
</head>
<body>
    <?php include 'admin_navigasi.php'; ?>
    <div class="container my-5 pt-3">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white"><h5>Formulir Tambah Tanaman</h5></div>
            <div class="card-body p-4">
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3"><label for="nama">Nama Tanaman</label><input id="nama" name="nama" class="form-control" required></div>
                    <div class="mb-3"><label for="kategori">Kategori</label><select name="kategori" id="kategori" class="form-select" required><option value="" disabled selected>-- Pilih Kategori --</option><?php if($kategori_q) { while ($kat = mysqli_fetch_assoc($kategori_q)) : ?><option value="<?= htmlspecialchars($kat['nama']) ?>"><?= htmlspecialchars($kat['nama']) ?></option><?php endwhile; } ?></select></div>
                    <hr class="my-4">
                    <div class="mb-3"><label for="deskripsi_en">Deskripsi (English)</label><textarea id="deskripsi_en" name="deskripsi_en" class="form-control"></textarea></div>
                    <small class="form-text text-muted">
                        Untuk menyisipkan video, tulis penanda <strong>[YOUTUBE_VIDEO]</strong> di dalam teks di atas.
                    </small>
                    <div class="mb-3"><label for="deskripsi">Deskripsi (Indonesia)</label><textarea id="deskripsi" name="deskripsi" class="form-control" required></textarea></div>
                    <small class="form-text text-muted">
                        Untuk menyisipkan video, tulis penanda <strong>[YOUTUBE_VIDEO]</strong> di dalam teks di atas.
                    </small>
                    <div class="mb-3"><label for="link_referensi">Link Sumber Referensi (Opsional)</label><input type="url" id="link_referensi" name="link_referensi" class="form-control" placeholder="https://en.wikipedia.org/wiki/..."></div>
                    
                    <hr class="my-4">

                    <div class="mb-3">
                        <label for="video_youtube" class="form-label fw-bold">Kode Embed Video YouTube (Opsional)</label>
                        <textarea class="form-control" id="video_youtube" name="video_youtube" rows="4" placeholder="Contoh: <iframe width='560' height='315' src='...' ...></iframe>"></textarea>
                        <small class="form-text text-muted">Buka YouTube > Bagikan > Sematkan (Embed) > Salin kode iframe.</small>
                    </div>

                    <hr class="my-4">
                    
                    <div class="mb-3"><label for="gambar" class="form-label fw-bold">Gambar Utama</label><input type="file" id="gambar" name="gambar" class="form-control" accept="image/*" required></div>
                    <button type="submit" name="simpan" class="btn btn-primary">Simpan Data</button>
                </form>
            </div>
        </div>
    </div>
    
    <script src="<?= BASE_URL ?>js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>js/script.js"></script>
    <script>
        CKEDITOR.replace('deskripsi');
        CKEDITOR.replace('deskripsi_en');
    </script>
</body>
</html>