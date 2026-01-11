# 🏘️ SIPWARGA - Sistem Informasi Pelayanan Warga

**Sistem Manajemen Data Warga & Pengajuan Surat Digital**

[![Version](https://img.shields.io/badge/version-2.0-blue.svg)](CHANGELOG.md)
[![PHP](https://img.shields.io/badge/PHP-7.4+-purple.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange.svg)](https://mysql.com)
[![License](https://img.shields.io/badge/license-Proprietary-green.svg)](LICENSE)

---

## 📖 Tentang SipWarga

**SipWarga** adalah sistem informasi terintegrasi untuk memudahkan pengelolaan data warga dan proses administrasi RT/RW secara digital. Sistem ini dirancang untuk meningkatkan efisiensi pelayanan kepada warga dan mempermudah tugas administrasi ketua RT/RW.

### 🎯 Tujuan
- Digitalisasi data warga dan keluarga
- Mempercepat proses pengajuan surat administrasi
- Meningkatkan transparansi dan akuntabilitas
- Memudahkan monitoring dan pelaporan
- Mengurangi penggunaan kertas (paperless)

---

## ✨ Fitur Utama

### 👤 Untuk Warga
- ✅ **Registrasi & Login** - Pendaftaran akun dengan verifikasi admin
- ✅ **Dashboard Pribadi** - Overview data dan status pengajuan
- ✅ **Data Pribadi** - Kelola informasi pribadi lengkap
- ✅ **Data Keluarga** - Input data anggota keluarga (KK)
- ✅ **Pengajuan Surat** - Ajukan berbagai jenis surat administrasi
- ✅ **Upload Dokumen** - Lampirkan file pendukung (PDF/JPG)
- ✅ **Track Status** - Pantau status pengajuan real-time
- ✅ **Riwayat** - Lihat history semua aktivitas
- ✅ **Pengaturan** - Update profil dan ganti password

### 👨‍💼 Untuk Admin/Ketua RT
- ✅ **Dashboard Admin** - Statistik lengkap dan analytics
- ✅ **Manajemen Warga** - CRUD data warga
- ✅ **Verifikasi Akun** - Approve/reject pendaftaran warga
- ✅ **Proses Pengajuan** - Review dan proses pengajuan surat
- ✅ **Upload Hasil** - Upload surat yang sudah jadi (PDF)
- ✅ **Manajemen Ketua RT/RW** - Data pengurus
- ✅ **Laporan & Statistik** - Demographics, grafik, dan report

---

## 🛠️ Teknologi

- **PHP 7.4+** dengan MVC Pattern
- **MySQL 5.7+** dengan Foreign Key Constraints
- **REST API** untuk komunikasi client-server
- **Bootstrap 5** untuk responsive UI
- **Bcrypt** untuk password encryption
- **Prepared Statements** untuk SQL injection prevention

---

## 📦 Instalasi Cepat

1. **Extract & Copy**
   ```bash
   unzip sipwarga.zip -d /path/to/webserver/
   ```

2. **Create Database**
   ```sql
   CREATE DATABASE asiap_warga;
   ```

3. **Import Database**
   ```bash
   mysql -u root -p asiap_warga < assets/database/asiap_warga_complete.sql
   ```

4. **Configure**
   Edit `includes/config.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'asiap_warga');
   ```

5. **Setup Permissions**
   ```bash
   chmod -R 777 uploads/
   ```

6. **Access**
   ```
   http://localhost/sipwarga/
   ```

### 🔑 Default Admin
```
NIK:      1234567890123456
Password: admin123
```

> **⚠️ PENTING:** Ganti password setelah login!

---

## 📚 Dokumentasi Lengkap

- **[INSTALASI.md](INSTALASI.md)** - Panduan instalasi detail + troubleshooting
- **[DOKUMENTASI_API.md](DOKUMENTASI_API.md)** - API endpoints documentation
- **[CHANGELOG.md](CHANGELOG.md)** - Version history & changes

---

## 🔐 Keamanan

- ✅ Password encryption dengan bcrypt
- ✅ SQL injection prevention
- ✅ XSS protection
- ✅ CSRF token validation
- ✅ Session security
- ✅ File upload validation
- ✅ Role-based access control

---

## 📊 System Requirements

**Minimum:**
- PHP 7.4, MySQL 5.7, Apache 2.4
- 100MB disk, 512MB RAM

**Recommended:**
- PHP 8.0+, MySQL 8.0+
- 500MB disk, 1GB RAM

**Extensions:** mysqli, session, fileinfo, mbstring

---

## 🚀 API Overview

### Authentication
```http
POST /login.php
nik=1234567890123456&password=admin123
```

### Get Dashboard Stats
```http
GET /api/dashboard.php
Cookie: PHPSESSID=abc123
```

### Create Submission
```http
POST /api/pengajuan_surat.php
Content-Type: application/json

{
  "jenis_surat": "Surat Keterangan Domisili",
  "keperluan": "Keperluan bank"
}
```

Lihat [DOKUMENTASI_API.md](DOKUMENTASI_API.md) untuk detail lengkap.

---

## 🐛 Troubleshooting

### Database Connection Failed
```php
// Check: includes/config.php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### Upload Not Working
```bash
chmod -R 777 uploads/
```

### Session Issues
```php
echo session_save_path(); // Check writable
```

Lihat [INSTALASI.md](INSTALASI.md) untuk solusi lengkap.

---

## 📈 Roadmap

**v2.1 (Q2 2026)**
- Email notifications
- SMS gateway
- Export PDF/Excel
- Multi-language

**v3.0 (Q4 2026)**
- PWA support
- Real-time notifications
- Mobile apps
- AI features

---

## 📞 Support

- 📧 Email: support@sipwarga.com
- 📖 Docs: https://docs.sipwarga.com
- 🐛 Issues: GitHub Issues

---

## 📄 License

Copyright © 2026 SipWarga Development Team. All rights reserved.

---

<p align="center">
  <strong>Made with ❤️ by SipWarga Team</strong><br>
  <sub>Version 2.0 | Last Updated: 2026-01-09</sub>
</p>
