<?php
// FILE DETAIL_TANAMAN (BAGIAN ATAS) - VERSI REVISI FINAL

require_once 'config.php';
// session_start(); // Tidak perlu session jika halaman ini untuk publik

// 1. Ambil ID dari URL dan pastikan itu adalah angka yang valid
$id = intval($_GET['id'] ?? 0);
if ($id === 0) {
    die("Halaman tidak ditemukan: ID Tanaman tidak valid.");
}

// 2. Gunakan PREPARED STATEMENT untuk mengambil data (Sangat Aman)
$stmt = $conn->prepare("SELECT * FROM tanaman WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Tanaman dengan ID tersebut tidak ditemukan.");
}
$data = $result->fetch_assoc();
$stmt->close();

// 3. Siapkan data untuk dibagikan (URL & Teks)
$share_url = BASE_URL . "detail_tanaman.php?id=" . $data['id'];
$share_text_twitter = "Lihat informasi menarik tentang " . htmlspecialchars($data['nama']) . "!";
$share_text_whatsapp = "Hai! Cek info menarik tentang " . htmlspecialchars($data['nama']) . " di sini: " . $share_url;
$encoded_url = urlencode($share_url);
$encoded_text_twitter = urlencode($share_text_twitter);
$encoded_text_whatsapp = urlencode($share_text_whatsapp);

// 4. Siapkan HTML untuk pemutar video (jika ada)
$video_player = '';
if (!empty($data['video_youtube'])) {
    // Bungkus dengan div responsif dari Bootstrap
    $video_player = "<div class='ratio ratio-16x9 my-4 shadow-sm rounded'>" . $data['video_youtube'] . "</div>";
}

// 5. Siapkan HTML untuk semua tombol di bawah deskripsi (Action Bar)
$actionBarHtml = ''; // Mulai dengan string kosong
$tombolReferensiHtml = '';
$tombolBagikanHtml = '';

// Cek dan buat tombol link referensi
if (!empty($data['link_referensi'])) {
    $link = htmlspecialchars($data['link_referensi']);
    $tombolReferensiHtml = "<a href='{$link}' class='btn btn-outline-success' target='_blank' rel='noopener noreferrer'>Baca Sumber Referensi</a>";
}

// Buat tombol bagikan
$tombolBagikanHtml = "
    <div class='mt-3'>
        <strong class='me-2'>Bagikan:</strong>
        <a href='https://www.facebook.com/sharer/sharer.php?u={$encoded_url}' target='_blank' rel='noopener noreferrer' class='btn btn-sm btn-share btn-facebook'><i data-feather='facebook'></i></a>
        <a href='https://twitter.com/intent/tweet?url={$encoded_url}&text={$encoded_text_twitter}' target='_blank' rel='noopener noreferrer' class='btn btn-sm btn-share btn-twitter'><i data-feather='twitter'></i></a>
        <a href='https://api.whatsapp.com/send?text={$encoded_text_whatsapp}' target='_blank' rel='noopener noreferrer' class='btn btn-sm btn-share btn-whatsapp'><i data-feather='message-circle'></i></a>
    </div>
";

// Gabungkan semuanya dalam satu div "action bar"
if (!empty($tombolReferensiHtml)) {
    $actionBarHtml .= $tombolReferensiHtml;
}
// Selalu tambahkan tombol bagikan
$actionBarHtml .= $tombolBagikanHtml;

if (!empty($actionBarHtml)) {
    $actionBarHtml = "<div class='mt-4 border-top pt-3'>{$actionBarHtml}</div>";
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($data['nama']) ?> - Detail Tanaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/feather-icons"></script>

    <style>
        /* ------------ BODY & BACKGROUND ------------ */
        body {
            position: relative;
            font-family: 'Poppins', sans-serif;
            background: #f0fdf4; /* fallback background */
            color: #222;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background-image: url("images/pattern2.png");
            background-repeat: repeat;
            background-size: 250px 250px;
            z-index: -1;
            pointer-events: none;
            opacity: 1; /* Tidak perlu diturunkan lagi karena SVG-nya sudah transparan */
        }

        html, body {
            height: 100%;
        }

        /* ------------ CONTAINER ------------ */
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
            position: relative;
            z-index: 10;
        }

        /* ------------ DETAIL WRAPPER ------------ */
        .detail-wrapper {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }

        /* ------------ GAMBAR TANAMAN ------------ */
        .img-container {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        /* ------------ DETAIL KONTEN ------------ */
        .detail-wrapper {
            max-width: 960px;
            margin: auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.93);
            border: 3px solid #c7eacb;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            position: relative;
            z-index: 10;
        }

        .tanaman-img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            object-position: center;
            border-radius: 10px;
            border: 2px solid #a4d4aa;
        }

        .deskripsi-box {
            background: #f8fdf8;
            padding: 15px 20px;
            border-left: 6px solid #81c784;
            margin-top: 15px;
            font-size: 1rem;
            line-height: 1.6;
            will-change: opacity, transform;
        }

        .btn-back {
            margin-top: 20px;
            background-color: #388e3c;
            border: none;
            padding: 10px 20px;
            color: white;
            font-weight: 600;
            border-radius: 8px;
        }

        .btn-back:hover {
            background-color: #2e7d32;
        }

        .footer-note {
            font-size: 0.9rem;
            text-align: center;
            margin-top: 30px;
            color: gray;
        }

        /* ------------ NAMA TANAMAN ------------ */
        .nama-wrapper {
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
            padding: 10px 0;
            border-bottom: 1px solid #ccc;
        }

        .nama-tanaman {
            font-size: 3rem;
            color: #2e7d32;
            margin: 0;
            transform-origin: top center;
            display: block;
            text-align: center;
        }

        /* NEW STYLES for aligning category and language select */
        .header-controls {
            display: flex;
            justify-content: space-between; /* Puts items at opposite ends */
            align-items: center; /* Vertically centers them */
            margin-bottom: 15px; /* Space between this row and description */
        }

        @media (max-width: 576px) {
            .nama-tanaman {
                font-size: 2.2rem;
            }
            .tanaman-img {
                height: 220px;
            }
            /* Adjust for smaller screens: stack elements vertically */
            .header-controls {
                flex-direction: column;
                align-items: flex-start; /* Align to the left when stacked */
            }
            .header-controls .badge {
                margin-bottom: 10px; /* Add space between badge and select */
            }
            .header-controls .text-end { /* Adjust select dropdown alignment for small screens */
                text-align: left !important; /* Override Bootstrap's text-end */
                width: 100%; /* Make select full width */
            }
        }

        /* ------------ PADDING TAMBAHAN (opsional) ------------ */
        body.navbar-active {
            padding-top: 60px;
        }

        .logo-item {
            height: 35px; /* Atur tinggi seragam untuk semua logo */
            width: auto;
            object-fit: contain;
         }
    

    </style>

</head>
<body>

<body>

    <a href="<?= BASE_URL ?>index.php" class="back-button" aria-label="Kembali">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
    </a>

    <div class="container py-4">
        <div class="detail-wrapper">

            <div class="d-flex align-items-center gap-3 mb-3">
                <img src="images/logo_2.png" alt="Logo Politeknik Negeri Bali"   class="logo-item">
                <img src="images/logo_3.png" alt="Logo Amerta Zamaniya"  class="logo-item">
                <img src="images/logo_4.png" alt="Logo Diva Cipta"   class="logo-item">
                <img src="images/logo.png"   alt="Logo Desa Wisata Taro" class="logo-item">
                <span style="font-size: 1.1rem; font-weight: 600;">Desa Wisata Taro</span>
            </div>

            <div class="img-container mb-3">
                <img src="uploads/<?= htmlspecialchars($data['gambar']) ?>" alt="<?= htmlspecialchars($data['nama']) ?>" class="tanaman-img w-100 rounded">
            </div>

            <div class="nama-wrapper">
                <h2 class="nama-tanaman fw-bold"><?= htmlspecialchars($data['nama']) ?></h2>
            </div>

            <div class="header-controls">
                <?php
                  $kategori = strtolower($data['kategori'] ?? '');
                  $badgeClass = match ($kategori) {
                      'sayuran' => 'bg-success',
                      'daun hias' => 'bg-info text-dark',
                      'obat'      => 'bg-danger',
                      default     => 'bg-secondary'
                  };
                ?>
                <span class="badge <?= $badgeClass ?>" style="font-size: 0.9rem;">
                    🌿 Kategori: <?= htmlspecialchars($data['kategori']) ?>
                </span>
                
                <div class="text-end"> <label class="me-2 fw-semibold">Select Language:</label>
                    <select id="languageSelect" class="form-select d-inline w-auto">
                        <option value="id">🇮🇩 Indonesia</option>
                        <option value="en" selected>🇬🇧 English</option>
                    </select>
                </div>
            </div>

            <div class="deskripsi-box" id="desc-en">
                <?php
                $deskripsi_lengkap = $data['deskripsi_en'] ?? 'Description not available yet.';
                echo str_replace('[YOUTUBE_VIDEO]', $video_player, $deskripsi_lengkap);
                echo $actionBarHtml;
                ?>
            </div>

            <div class="deskripsi-box" id="desc-id" style="display: none;">
                <?php
                $deskripsi_lengkap = $data['deskripsi'] ?? 'Deskripsi belum tersedia.';
                echo str_replace('[YOUTUBE_VIDEO]', $video_player, $deskripsi_lengkap);
                echo $actionBarHtml;
                ?>
            </div>

        </div> <div class="footer-note">
            Dikelola oleh Desa Wisata Taro.
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <script>
        gsap.registerPlugin(ScrollTrigger);

        // Animasi scroll untuk nama tanaman (Keeping original animation as requested)
        gsap.fromTo(".nama-tanaman", 
            { scale: 2, y: 30, opacity: 0.9 },
            {
                scale: 1,
                y: 0,
                opacity: 1,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: ".img-container",
                    start: "bottom bottom",
                    end: "top top",
                    scrub: 0.4
                }
            }
        );

        document.addEventListener("DOMContentLoaded", function () {
            const selectLang = document.getElementById("languageSelect");
            const descId = document.getElementById("desc-id");
            const descEn = document.getElementById("desc-en");

            function toggleLanguage(lang) {
                const targetIn = (lang === "id") ? descId : descEn;
                const targetOut = (lang === "id") ? descEn : descId;

                // Sembunyikan yang tidak dipakai
                targetOut.style.display = "none";

                // Tampilkan dan animasikan yang dipilih
                targetIn.style.display = "block";
                gsap.fromTo(targetIn, 
                    { opacity: 0, y: 30 },
                    { opacity: 1, y: 0, duration: 1, ease: "power2.out" }
                );
            }

            // Animasi pertama saat halaman dimuat
            toggleLanguage(selectLang.value);

            // Jalankan animasi saat pengguna mengganti bahasa
            selectLang.addEventListener("change", function () {
                toggleLanguage(this.value);
            });
        });
    </script>

    <script src="<?= BASE_URL ?>js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>js/script.js"></script>

    <script>
      feather.replace();
    </script>
</body>
</html>