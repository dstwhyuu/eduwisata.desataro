<?php
include 'config.php';
// Memastikan session_start() dipanggil di config.php atau di sini
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['login'])) header("Location: login.php");

// PAGING
$per_page = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$start = ($page - 1) * $per_page;

// Pencarian
$search = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';
$where = '';
if ($search != '') {
    $where = "WHERE nama LIKE '%$search%' OR deskripsi LIKE '%$search%'";
}

// Hitung total data
$total_q = mysqli_query($conn, "SELECT COUNT(*) as total FROM tanaman $where");
// Menambahkan pemeriksaan kesalahan untuk query
if (!$total_q) {
    die('Query Error: ' . mysqli_error($conn));
}
$total_row = mysqli_fetch_assoc($total_q);
$total_data = $total_row['total'];
$total_page = ceil($total_data / $per_page);

// Ambil data dengan limit
$q = mysqli_query($conn, "SELECT * FROM tanaman $where ORDER BY id DESC LIMIT $start, $per_page");
// Menambahkan pemeriksaan kesalahan untuk query
if (!$q) {
    die('Query Error: ' . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Tanaman</title>
    <link href="<?= BASE_URL ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>css/style.css" rel="stylesheet">
    
    <script src="<?= BASE_URL ?>js/bootstrap.bundle.min.js" defer></script>
    
</head>
<body>
    <?php include 'admin_navigasi.php'; ?>
    <div style="margin-top: 80px";>
<div class="container">

    <form class="mb-3" method="get" action="">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Cari nama atau deskripsi tanaman..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
            <button class="btn btn-success" type="submit">Cari</button>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-header text-white">
            <h4 class="mb-0">🌱 Daftar Tanaman</h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th class="text-center">Gambar</th>
                            <th class="text-center">QR Code</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = $start + 1;
                    while($row = mysqli_fetch_assoc($q)): ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td>
                                <?php
                                $plain = $row['deskripsi'];
                                $max = 100;
                                if (mb_strlen($plain) > $max) {
                                    $plain = mb_substr($plain, 0, $max);
                                    $plain = mb_substr($plain, 0, mb_strrpos($plain, ' '));
                                    $plain .= '...';
                                }
                                echo $plain;
                                ?>
                            </td>
                            <td class="text-center">
                                <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($row['gambar']) ?>" width="80" class="img-thumbnail" alt="Gambar Tanaman">
                            </td>
                            <td class="text-center">
                                <img src="qrcodes/<?= htmlspecialchars($row['qrcode']) ?>" width="80" class="img-thumbnail" alt="QR Code">
                                <br>
                                <a href="print_qrcode.php?id=<?= $row['id'] ?>" target="_blank" class="btn btn-sm btn-outline-secondary mt-2">Cetak QR</a>
                            </td>
                            <td class="text-center">
                                <div class="aksi-btns justify-content-center">
                                    <a href="detail_tanaman.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info text-white">Detail</a>
                                    <a href="edit_tanaman.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning text-white">Edit</a>
                                    <a href="hapus_tanaman.php?id=<?= $row['id'] ?>" onclick="return confirm('Hapus?')" class="btn btn-sm btn-danger">Hapus</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    <?php if ($total_data == 0): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Data tidak ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <nav class="mt-3">
        <ul class="pagination justify-content-center">
            <?php $url_q = $search != '' ? '&q='.urlencode($search) : ''; ?>
            <?php if ($page > 1): ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $page-1 . $url_q ?>">Sebelumnya</a></li>
            <?php endif; ?>
            <?php for ($i=1; $i<=$total_page; $i++): ?>
                <li class="page-item <?= ($page==$i)?'active':'' ?>">
                    <a class="page-link" href="?page=<?= $i . $url_q ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <?php if ($page < $total_page): ?>
                <li class="page-item"><a class="page-link" href="?page=<?= $page+1 . $url_q ?>">Berikutnya</a></li>
            <?php endif; ?>
        </ul>
    </nav>

</div>

<footer class="text-center fixed-bottom">
    <div class="container">
    Desa Wisata Taro<br>
    </div>
</footer>
<script src="<?= BASE_URL ?>js/script.js"></script>

</body>
</html>