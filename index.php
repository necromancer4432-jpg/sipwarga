<?php
session_start();

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['id_user'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: views/admin/dashboard_rt.php");
    } else {
        header("Location: views/warga/dashboard_warga.php");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>SipWarga!</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/Css/style.css" />
</head>
<body>

  <!-- Header -->
  <header id="mainHeader">
    <div class="header-inner">
      <div class="logo">SipWarga!</div>
      <nav>
        <ul>
          <li><a href="#beranda" data-target="beranda" class="nav-link">Beranda</a></li>
          <li><a href="#tentang" data-target="tentang" class="nav-link">Tentang</a></li>
          <li><a href="#panduan" data-target="panduan" class="nav-link">Panduan</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- BERANDA -->
  <section id="beranda" class="section beranda">
    <div class="beranda-left">
      <h2>Halo, selamat datang!</h2>
      <h1>Kelola Data Warga dengan<br/>Cepat, Tepat, dan Aman.</h1>
      <p>Solusi digital untuk mencatat, mengelola, dan memantau data warga.</p>

      <div class="btns">
        <button class="btn btn-daftar">
         <a href ="Registrasi.php">Daftar </a></button>
        <button class="btn btn-masuk">
          <a href ="login.php">Masuk </a>
        </button>
      </div>
    </div>

    <div class="beranda-right">
      <!--image -->
      <img src="assets/images/Group.png" alt="ilustrasi" />
    </div>
  </section>

  <!-- TENTANG -->
  <section id="tentang" class="section tentang">
    <div class="tentang-box">
      <h2>Tentang SipWarga!</h2>
      <p>
        SipWarga adalah aplikasi web yang dirancang untuk membantu warga dan ketua RT/RW
        dalam mengelola data administrasi warga secara mudah, cepat, dan efisien.
        Melalui web ini, kegiatan seperti pencatatan data penduduk dan penyampaian informasi
        dapat dilakukan secara digital. Tujuan utama adalah mempercepat pelayanan administrasi,
        meningkatkan akurasi data, serta mengurangi penggunaan berkas manual.
      </p>
    </div>
  </section>

  <!-- PANDUAN -->
  <section id="panduan" class="section panduan">
    </div>
    <div class="cards">
      <div class="card">
       
        <h2>Login/Registrasi<h2>
          <div class="d_login">
          <p>Pengguna masuk dengan akun yang telah terdaftar. Jika belum punya akun,
            lakukan registrasi dengan mengisi data diri lengkap
          </p>
          </div>
      </div>
      <div class="card">
         <h2>Beranda</h2>
         <div class="d_Beranda">
          <p>Menampilkan ringkasan data warga dan imformasi umum.
            terdapat menu navigasi menuju fitur utama
          </p>
        </div>
       </div>

      <div class="card">
        <h2>Profil</h2> <!-- card 3 --> 
        <div class="d_profil">
          <p>Tambahkan, ubah, atau hapus data pribadi </p>
          <p>[nama,NIK, dan alamat]</p>
        </div>
      </div>
      <div class="card">
        <h2>Data Keluarga</h2> <!-- card 4 --> 
        <div class="d_keluarga">
          <p>Tambahkan, ubah, atau hapus data keluarga [Nama, NIK,  alamat dan jumlah anggota keluarga]</p>
        </div>
      </div>
    </div>
  </section>
  
  
  <footer>
    <div class="footer">
      <div class="sipwarga">
        <h4>SipWarga!</h4>
          <p>Sistem informasi Pencatatan Warga untuk kemudahan akan pencatatan Administrasi
        Warga
        </p>
      </div>
      <div class="Bantuan">
        <h4>Bantuan</h4>
          <p>Panduan penggunaan FaQ Hubungi kami Laporan masalah</p>
      </div>
      <div class="kontak">
        <h4>Kontak</h4>
          <p>000000</p>
          <p>Batam,indonesia</p>
      </div>
    </div>
  </footer>

  <script src="assets/JavaScript/main.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>