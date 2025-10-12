<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Kontak</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Kirim Email via PHPMailer</h3>
                    </div>
                    <div class="card-body">
                    //Tambahkan kode PHP ini di dalam file kontak.php
                        // Letakkan tepat di atas tag <form>

                        <div class="card-body">
                            <?php
                            if (isset($_GET['status'])) {
                                if ($_GET['status'] == 'sukses') {
                                    echo '<div class="alert alert-success" role="alert">Pesan Anda berhasil dikirim! Terima kasih.</div>';
                                } else if ($_GET['status'] == 'gagal') {
                                    echo '<div class="alert alert-danger" role="alert">Pesan gagal dikirim. Error: ' . htmlspecialchars($_GET['error']) . '</div>';
                                }
                            }
                            ?>
                            <form action="kirim-email.php" method="post">
                                ```
                        <form action="kirim-email.php" method="post">
                            <div class="mb-3">
                                <label for="email_penerima" class="form-label">Email Penerima:</label>
                                <input type="email" class="form-control" id="email_penerima" name="email_penerima" required>
                            </div>
                            <div class="mb-3">
                                <label for="subjek" class="form-label">Subjek:</label>
                                <input type="text" class="form-control" id="subjek" name="subjek" required>
                            </div>
                            <div class="mb-3">
                                <label for="pesan" class="form-label">Pesan:</label>
                                <textarea class="form-control" id="pesan" name="pesan" rows="5" required></textarea>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Kirim Email</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>