<?php
require_once 'includes/config.php';

$nik = '1234567890123456'; // NIK ADMIN
$password_baru = 'admin123'; // GANTI SESUAI MAU

$hash = password_hash($password_baru, PASSWORD_DEFAULT);

$q = mysqli_query($koneksi, "
    UPDATE warga 
    SET password = '$hash'
    WHERE nik = '$nik'
    AND role = 'admin'
");

if ($q && mysqli_affected_rows($koneksi) > 0) {
    echo "✅ Password admin berhasil direset.<br>";
    echo "NIK: $nik<br>";
    echo "Password baru: $password_baru";
} else {
    echo "❌ Gagal reset password admin.";
}
