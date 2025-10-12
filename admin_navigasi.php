<nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top shadow-sm">
    <div class="container d-flex align-items-center">
        <a class="navbar-brand d-flex align-items-center" href="<?= BASE_URL ?>index.php" style="gap: 10px;">
            <span class="fw-semibold fs-5">Desa Wisata Taro</span>
        </a>

        <div id="hamburger" class="hamburger-menu">
            <div class="bar"></div><div class="bar"></div><div class="bar"></div>
        </div>

        <div id="menuOverlay" class="menu-overlay">
            <div class="menu-overlay-wrapper">
                <div class="menu-header">
                    <img src="<?= BASE_URL ?>images/LOGO 2.png" class="menu-logo" alt="Logo Eduwisata Taro"/>
                    <div class="menu-title"> Desa Wisata Taro</div>
                </div>
                <div class="menu-overlay-content">
                    <a href="<?= BASE_URL ?>ganti_password.php" class="menu-item">
                        <span class="menu-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></span>
                        Ganti Password
                        <span class="menu-arrow"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg></span>
                    </a>
                    <a href="<?= BASE_URL ?>tambah_tanaman.php" class="menu-item">
                        <span class="menu-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg></span>
                        Tambah Tanaman
                        <span class="menu-arrow"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg></span>
                    </a>
                    <a href="<?= BASE_URL ?>pengaturan-email.php" class="menu-item">
                        <span class="menu-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg></span>
                        Pengaturan Email
                        <span class="menu-arrow"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg></span>
                    </a>
                </div>
                <div class="menu-footer">
                    <a href="<?= BASE_URL ?>logout.php" class="btn-logout">Logout</a>
                </div>
            </div>
        </div>
        <div class="collapse navbar-collapse justify-content-end" id="navbarTaro">
            <ul class="navbar-nav align-items-center gap-2">
                <li class="nav-item"><a href="<?= BASE_URL ?>tambah_tanaman.php" class="btn btn-outline-light btn-sm">+ Tambah Tanaman</a></li>
                <li class="nav-item"><a href="<?= BASE_URL ?>ganti_password.php" class="btn btn-outline-light btn-sm">Ganti Password</a></li>
                <li class="nav-item"><a href="<?= BASE_URL ?>logout.php" class="btn btn-light btn-sm">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>