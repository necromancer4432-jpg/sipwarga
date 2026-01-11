<?php
// ==============================
// FUNCTIONS GLOBAL SIPWARGA
// ==============================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ================= LOGIN & ROLE ================= */

// Cek login
function isLoggedIn() {
    return isset($_SESSION['id_user'], $_SESSION['nik'], $_SESSION['role']);
}

// Cek admin
function isAdmin() {
    return isLoggedIn() && $_SESSION['role'] === 'admin';
}

// Cek warga
function isWarga() {
    return isLoggedIn() && $_SESSION['role'] === 'warga';
}

// Wajib login
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /sipwarga/login.php");
        exit;
    }
}

// Wajib admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header("Location: /sipwarga/views/warga/dashboard_warga.php");
        exit;
    }
}

// Wajib warga
function requireWarga() {
    requireLogin();
    if (!isWarga()) {
        header("Location: /sipwarga/login.php");
        exit;
    }
}

/* ================= SECURITY ================= */

// Bersihkan input (UNTUK QUERY)
function clean($data) {
    global $koneksi;
    return mysqli_real_escape_string($koneksi, trim($data));
}

// Escape output HTML
function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Validasi NIK (16 digit)
function validateNIK($nik) {
    return preg_match('/^[0-9]{16}$/', $nik);
}

/* ================= UTIL ================= */

// Format tanggal Indonesia
function formatTanggal($tanggal) {
    if (!$tanggal) return '-';

    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $split = explode('-', substr($tanggal, 0, 10));
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

// JSON response (AJAX)
function jsonResponse($success, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode(compact('success','message','data'));
    exit;
}

/* ================= ALERT ================= */

function setAlert($type, $message) {
    $_SESSION['alert'] = [
        'type' => $type,
        'message' => $message
    ];
}

function showAlert() {
    if (!empty($_SESSION['alert'])) {
        $a = $_SESSION['alert'];
        $class = match($a['type']) {
            'success' => 'alert-success',
            'error'   => 'alert-danger',
            'warning' => 'alert-warning',
            default   => 'alert-info'
        };

        echo '<div class="alert '.$class.'">';
        echo e($a['message']);
        echo '</div>';

        unset($_SESSION['alert']);
    }
}

/* ================= UPLOAD ================= */

function uploadFile($file, $dir = '/sipwarga/uploads/') {

    if (empty($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $allowed = ['image/jpeg','image/png','image/jpg','application/pdf'];
    if (!in_array($file['type'], $allowed)) return false;

    if ($file['size'] > 5 * 1024 * 1024) return false;

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $name = uniqid('file_', true) . '.' . $ext;

    $targetDir = $_SERVER['DOCUMENT_ROOT'] . $dir;
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    if (move_uploaded_file($file['tmp_name'], $targetDir . $name)) {
        return $name;
    }

    return false;
}
