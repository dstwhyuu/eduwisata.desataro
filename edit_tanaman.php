<?php
// FILE EDIT_TANAMAN VERSI FINAL (Revisi Terakhir)

require_once 'config.php';
session_start();
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit; }

$id = intval($_GET['id'] ?? 0);
if ($id === 0) {
    header("Location: index.php");
    exit;
}

$error = '';
$message = '';

// Proses UPDATE jika form disubmit
if (isset($_POST['update'])) {
    
    // 1. Ambil semua data dari form, termasuk video_youtube
    $nama = $_POST['nama'] ?? null;
    $kategori = $_POST['kategori'] ?? null;
    // Ambil semua kolom lain yang Anda miliki di form
    $deskripsi = $_POST['deskripsi'] ?? null;
    $deskripsi_en = $_POST['deskripsi_en'] ?? null;
    $link_referensi = $_POST['link_referensi'] ?? null;
    $video_youtube = $_POST['video_youtube'] ?? null; // <-- Data video diambil
    $gambarLama = $_POST['gambar_lama'] ?? null;

    // 2. Logika untuk memproses gambar
    $namaFileGambar = $gambarLama;
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK && !empty($_FILES['gambar']['name'])) {
        if (!empty($gambarLama) && file_exists('uploads/' . $gambarLama)) {
            unlink('uploads/' . $gambarLama);
        }
        $gambarName = $_FILES['gambar']['name'];
        $gambarTmp = $_FILES['gambar']['tmp_name'];
        $namaFileGambar = uniqid() . '-' . strtolower(str_replace(' ', '-', $gambarName));
        move_uploaded_file($gambarTmp, 'uploads/' . $namaFileGambar);
    }

    // 3. Query UPDATE yang aman dan LENGKAP dengan semua kolom
    $stmt = $conn->prepare(
        "UPDATE tanaman SET 
            nama = ?, kategori = ?, deskripsi = ?, deskripsi_en = ?, 
            link_referensi = ?, gambar = ?, video_youtube = ?
         WHERE id = ?"
    );
    // 's' = string, 'i' = integer. Sesuaikan tipe dan urutan datanya.
    $stmt->bind_param("sssssssi", 
        $nama, $kategori, $deskripsi, $deskripsi_en, 
        $link_referensi, $namaFileGambar, 
        $video_youtube, // <-- Variabel video ditambahkan di sini
        $id
    );
    
    if ($stmt->execute()) {
        header("Location: index.php?status=sukses_edit");
        exit();
    } else {
        $error = "Gagal memperbarui data: " . $stmt->error;
    }
    $stmt->close();
}

// Ambil data yang ada untuk ditampilkan di form
$stmt_select = $conn->prepare("SELECT * FROM tanaman WHERE id = ?");
$stmt_select->bind_param("i", $id);
$stmt_select->execute();
$result = $stmt_select->get_result();
$data = $result->fetch_assoc();
if (!$data) {
    header("Location: index.php");
    exit;
}
$stmt_select->close();
$kategori_q = mysqli_query($conn, "SELECT * FROM kategori");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit: <?= htmlspecialchars($data['nama']) ?></title>
    <link href="<?= BASE_URL ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>css/style.css" rel="stylesheet">
    <script src="<?= BASE_URL ?>ckeditor/ckeditor.js"></script>
</head>
<body>
    <?php include 'admin_navigasi.php'; ?>

    <div class="container my-5 pt-3">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">📝 Edit Data: <?= htmlspecialchars($data['nama']) ?></h5>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
                
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="gambar_lama" value="<?= htmlspecialchars($data['gambar']) ?>">
                    
                    <div class="mb-3"><label for="nama" class="form-label fw-bold">Nama Tanaman</label><input type="text" id="nama" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama'] ?? '') ?>" required></div>
                    <div class="mb-3"><label for="kategori" class="form-label fw-bold">Kategori</label><select name="kategori" id="kategori" class="form-select" required><?php mysqli_data_seek($kategori_q, 0); while ($kat = mysqli_fetch_assoc($kategori_q)) : ?><option value="<?= htmlspecialchars($kat['nama']) ?>" <?= (isset($data['kategori']) && $data['kategori'] == $kat['nama']) ? 'selected' : '' ?>><?= htmlspecialchars($kat['nama']) ?></option><?php endwhile; ?></select></div>
                    <hr>
                    <div class="mb-3"><label for="deskripsi_en">Deskripsi (English)</label><textarea id="deskripsi_en" name="deskripsi_en" class="form-control"><?= htmlspecialchars($data['deskripsi_en'] ?? '') ?></textarea></div>
                    <div class="mb-3"><label for="deskripsi">Deskripsi (Indonesia)</label><textarea id="deskripsi" name="deskripsi" class="form-control" required><?= htmlspecialchars($data['deskripsi'] ?? '') ?></textarea></div>
                    <div class="mb-3"><label for="link_referensi">Link Sumber Referensi (Opsional)</label><input type="url" id="link_referensi" name="link_referensi" class="form-control" placeholder="https://..." value="<?= htmlspecialchars($data['link_referensi'] ?? '') ?>"></div>
                    <hr>
                    
                    <div class="mb-3">
                        <label for="video_youtube" class="form-label fw-bold">Kode Embed Video YouTube (Opsional)</label>
                        <textarea class="form-control" id="video_youtube" name="video_youtube" rows="4" placeholder="Contoh: <iframe ...></iframe>"><?= htmlspecialchars($data['video_youtube'] ?? '') ?></textarea>
                        <small class="form-text text-muted">Buka YouTube > Bagikan > Sematkan (Embed) > Salin kode iframe.</small>
                    </div>
                    <hr>

                    <div class="mb-3"><label for="gambar" class="form-label fw-bold">Gambar Saat Ini</label><br><img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($data['gambar']) ?>" width="150" class="img-thumbnail mb-2"><br><label for="gambar" class="form-label mt-2">Ganti Gambar (opsional)</label><input id="gambar" type="file" name="gambar" class="form-control" accept="image/*"></div>
                    
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="index.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" name="update" class="btn btn-success">Simpan Perubahan</button>
                    </div>
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