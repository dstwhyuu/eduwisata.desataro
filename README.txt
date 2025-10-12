# CRUD Tanaman + QR Code + Upload Foto + Login (PHP Native + MySQL)

## 📁 Struktur Folder
```
crud_tanaman_qrcode/
├── db.php
├── login.php
├── logout.php
├── index.php
├── tambah.php
├── edit.php
├── hapus.php
├── detail.php
├── foto/               <-- tempat simpan foto tanaman
├── qrcodes/            <-- tempat simpan QR Code
├── qrcode/          <-- library QR Code (harus diisi)
```

## 🛠️ Langkah Instalasi
1. **Pindahkan folder ini ke `htdocs/` (jika pakai XAMPP)**
2. **Import database**

### Struktur SQL
```sql
CREATE DATABASE tanaman_db;
USE tanaman_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(255)
);

INSERT INTO users (username, password) VALUES ('admin', MD5('admin123'));

CREATE TABLE tanaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    deskripsi TEXT,
    foto VARCHAR(255),
    qrcode VARCHAR(255)
);
```

3. **Download dan letakkan library QRCode di folder `phpqrcode/`**
   - Link: https://sourceforge.net/projects/phpqrcode/

4. **Akses dari browser**
   - `http://localhost/crud_tanaman_qrcode/login.php`

## 👤 Login Default
- Username: `admin`
- Password: `admin123`

## 📝 Fitur Aplikasi
- Login / logout
- Tambah, edit, hapus tanaman
- Upload foto tanaman
- Generate & tampilkan QR code otomatis
- Detail tanaman melalui scan QR code

