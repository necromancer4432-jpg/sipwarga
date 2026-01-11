<?php
/**
 * ============================================
 * Configuration File
 * ============================================
 */

// mulai session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'asiap_warga');

// Application Configuration
define('BASE_URL', 'http://localhost/sipwarga');
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB

// Connect to Database
$koneksi = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$koneksi) {
    die("Gagal terhubung ke database: " . mysqli_connect_error());
}

// Set charset
mysqli_set_charset($koneksi, "utf8mb4");

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Include helper functions
require_once __DIR__ . '/functions.php';

// Error reporting for development (disable in production) ini jangan diruabah ataupun dihapus
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
