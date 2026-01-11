<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
requireAdmin();

/* ================= TOTAL WARGA =================
   warga aktif + anggota keluarga
================================================= */
$qTotalWarga = mysqli_query($koneksi, "
    SELECT
      (SELECT COUNT(*) FROM warga WHERE role='warga' AND status='active')
    + (SELECT COUNT(*) FROM data_keluarga)
    AS total
");
$total_warga = mysqli_fetch_assoc($qTotalWarga)['total'];


/* ================= KEPALA KELUARGA ================= */
$qUser = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total
    FROM warga
    WHERE role='warga'
      AND status='active'
");
$total_user = mysqli_fetch_assoc($qUser)['total'];


/* ================= ADMIN ================= */
$qAdmin = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total
    FROM warga
    WHERE role='admin'
      AND status='active'
");
$total_admin = mysqli_fetch_assoc($qAdmin)['total'];


/* ================= MENUNGGU PERSETUJUAN =================
   - verifikasi akun warga
   - tambah anggota keluarga
   - pengajuan surat
========================================================= */
$qPending = mysqli_query($koneksi, "
    SELECT
      (
        (SELECT COUNT(*) FROM warga WHERE status='pending')
      + (SELECT COUNT(*) FROM pengajuan_keluarga WHERE status='pending')
      + (SELECT COUNT(*) FROM pengajuan_surat WHERE status='pending')
      ) AS total
");
$total_riwayat = mysqli_fetch_assoc($qPending)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - SipWarga!</title>

  <!-- CSS ADMIN -->
  <link rel="stylesheet" href="../../assets/Css/ketua_rt.css">

  <!-- ICON -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- STYLE TAMBAHAN -->
  <style>
    .stats-grid{
      display:grid;
      grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
      gap:20px;
      margin:20px 0
    }
    .stat-card{
      background:#fff;
      padding:20px;
      border-radius:8px;
      box-shadow:0 2px 4px rgba(0,0,0,0.1);
      text-align:center
    }
    .stat-card h3{
      margin:0;
      font-size:32px;
      color:#007bff
    }
    .stat-card p{
      margin:10px 0 0;
      color:#666
    }
  </style>
</head>

<body>

<div class="container">

  <!-- SIDEBAR -->
  <?php include '../../includes/sidebar_admin.php'; ?>

  <!-- KONTEN -->
  <main class="content">
    <h1>Dashboard Admin</h1>

    <!-- STATISTIK -->
     <div class="stats-grid">

  <div class="stat-card">
    <h3><?= $total_warga; ?></h3>
    <p><i class="fa-solid fa-users"></i> Total Warga</p>
  </div>

  <div class="stat-card">
    <h3><?= $total_user; ?></h3>
    <p><i class="fa-solid fa-user"></i> Kepala Keluarga</p>
  </div>

  <div class="stat-card">
    <h3><?= $total_admin; ?></h3>
    <p><i class="fa-solid fa-user-shield"></i> Admin</p>
  </div>

  <div class="stat-card">
    <h3><?= $total_riwayat; ?></h3>
    <p><i class="fa-solid fa-clock"></i> Menunggu Persetujuan</p>
  </div>

</div>

  </main>

</div>

</body>

</body>
</html>
