<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>FAQ - SipWarga</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <link rel="stylesheet" href="../../assets/Css/faq.css">
  <link rel="stylesheet" href="../../assets/Css/warga.css">
</head>
<body>

  <div class="container">
    <?php include '../../includes/sidebar_warga.php'; ?>

  <main class="content">
    <div class="faq-wrapper">
      <h1>Frequently Asked Questions</h1>

      <div class="faq-container">
        <div class="faq-item">
          <h3>Apa itu SipWarga?</h3>
          <p>SipWarga adalah aplikasi web untuk mengelola data administrasi warga.</p>
        </div>

        <div class="faq-item">
          <h3>Bagaimana cara mendaftar sebagai pengguna baru?</h3>
          <p>Klik "Daftar" di halaman utama lalu isi data diri Anda.</p>
        </div>

        <div class="faq-item">
          <h3>Apakah saya bisa mengubah data profil saya?</h3>
          <p>Buka menu "Laporan" dan pilih jenis laporan yang diinginkan.</p>
        </div>

        <div class="faq-item">
          <h3>Siapa yang bisa saya hubungi jika ada kendala?</h3>
          <p>Anda dapat menghubungi nomor yang tertera di bagian bawah halaman utama.</p>
        </div>
      </div>
    </div>
  </main>

</body>
</html>
