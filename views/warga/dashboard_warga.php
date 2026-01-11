<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
requireLogin();

/* ========================
   DATA SESSION USER
======================== */
$nik = $_SESSION['nik'];
$username = $_SESSION['username'] ?? 'User';

/* ========================
   CEK PROFIL LENGKAP
======================== */
$qProfile = mysqli_query($koneksi, "
    SELECT 1 FROM data_pribadi WHERE nik = '$nik'
");
$profileComplete = mysqli_num_rows($qProfile) > 0;

/* ========================
   HITUNG TOTAL DATA KELUARGA
   1 Kepala Keluarga
   + Anggota di data_keluarga
======================== */
$qAnggota = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total
    FROM data_keluarga
    WHERE nik_kepala = '$nik'
");

$rowAnggota = mysqli_fetch_assoc($qAnggota);
$totalKeluarga = 1 + $rowAnggota['total']; // +1 untuk kepala keluarga

/* ========================
   HITUNG RIWAYAT PENGAJUAN (APPROVED)
======================== */
$qRiwayat = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total
    FROM pengajuan_surat
    WHERE nik = '$nik'
      AND status = 'approved'
");

$rowRiwayat = mysqli_fetch_assoc($qRiwayat);
$totalRiwayat = $rowRiwayat['total'];

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SipWarga! - Beranda</title>
  <link rel="stylesheet" href="../../assets/Css/warga.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <div class="container">
    <?php include '../../includes/sidebar_warga.php'; ?>

    <main class="content">
      <h1>Beranda</h1>
      <div class="summary-cards">
        <div class="card">
          <i class="fa-solid fa-user-check icon"></i>
          <p>Profil lengkap</p>
          <span class="count"><?php echo $profileComplete ? '✓ Lengkap' : '⚠ Belum'; ?></span>
        </div>
        <div class="card">
          <i class="fa-solid fa-users icon"></i>
          <p>Total Data</p>
          <span class="count"><?php echo $totalKeluarga; ?></span>
        </div>
        <div class="card">
          <i class="fa-solid fa-clock icon"></i>
          <p>Riwayat</p>
          <span class="count"><?php echo $totalRiwayat; ?></span>
        </div>
      </div>
      <div class="welcome-card">
        <div class="progress-circle">
          <svg viewBox="0 0 36 36" class="circular-chart blue">
          </svg>
        </div>
        <div class="welcome-text">
          <h2>Halo, <?php echo htmlspecialchars($username); ?>!</h2>
          <p>Kelola data anda dan keluarga<br>dengan mudah di <strong>SipWarga!</strong></p>
        </div>
      </div>
    </main>
  </div>
</body>
</html>
