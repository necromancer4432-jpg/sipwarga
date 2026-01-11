<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
requireLogin();

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nik_kepala = $_SESSION['nik'];

    $nama_anggota      = clean($_POST['nama_anggota']);
    $nik_anggota       = clean($_POST['nik_anggota']);
    $tempat_lahir      = clean($_POST['tempat_lahir']);
    $tanggal_lahir     = $_POST['tanggal_lahir'];
    $jenis_kelamin     = clean($_POST['jenis_kelamin']);
    $hubungan_keluarga = clean($_POST['hubungan_keluarga']);
    $agama             = clean($_POST['agama']);

    /* 🔒 CEK DUPLIKASI SEBELUM INSERT */
    $cek = mysqli_query($koneksi, "
        SELECT 1
        FROM data_keluarga
        WHERE nik_anggota = '$nik_anggota'
    ");

    if (mysqli_num_rows($cek) > 0) {
        $message = 'NIK sudah terdaftar di keluarga lain';
        $message_type = 'danger';
    } else {

        $query = "INSERT INTO pengajuan_keluarga (
            nik_kepala,
            nama_anggota,
            nik_anggota,
            tempat_lahir,
            tanggal_lahir,
            jenis_kelamin,
            hubungan_keluarga,
            agama,
            status,
            created_at
        ) VALUES (
            '$nik_kepala',
            '$nama_anggota',
            '$nik_anggota',
            '$tempat_lahir',
            '$tanggal_lahir',
            '$jenis_kelamin',
            '$hubungan_keluarga',
            '$agama',
            'pending',
            NOW()
        )";

        if (mysqli_query($koneksi, $query)) {
            $message = 'Pengajuan berhasil dikirim, menunggu verifikasi admin';
            $message_type = 'success';
        } else {
            $message = mysqli_error($koneksi);
            $message_type = 'danger';
        }
    }
} // ✅ PENUTUP if POST
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Anggota Keluarga</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../assets/Css/warga.css">
  <link rel="stylesheet" href="../../assets/Css/data_keluarga.css">
  <style>
    .alert{padding:15px;margin-bottom:20px;border-radius:8px;display:<?php echo $message ? 'block' : 'none'; ?>}
    .alert-success{background:#d4edda;color:#155724;border:1px solid #c3e6cb}
    .form-group{margin-bottom:15px}
    .form-group label{display:block;margin-bottom:5px;font-weight:600}
    .form-group input,.form-group select{width:100%;padding:10px;border:1px solid #ddd;border-radius:4px}
  </style>
</head>
<body>
<div class="container">
    <?php include '../../includes/sidebar_warga.php'; ?>

  <main class="content">
    <h1>Tambah Anggota Keluarga</h1>
    
    <?php if($message): ?>
    <div class="alert alert-<?php echo $message_type; ?>">
      <?php echo htmlspecialchars($message); ?>
    </div>
    <?php endif; ?>

    <div class="card">
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Nama Lengkap Anggota <span style="color:red">*</span></label>
          <input type="text" name="nama_anggota" class="form-control" required placeholder="Masukkan nama lengkap">
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">NIK Anggota</label>
            <input type="text" name="nik_anggota" class="form-control" maxlength="16" placeholder="16 digit NIK">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Tempat Lahir</label>
            <input type="text" name="tempat_lahir" class="form-control" placeholder="Kota kelahiran">
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" class="form-control">
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Jenis Kelamin <span style="color:red">*</span></label>
            <select name="jenis_kelamin" class="form-select" required>
              <option value="">Pilih</option>
              <option value="Laki-laki">Laki-laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Hubungan dalam Keluarga <span style="color:red">*</span></label>
            <select name="hubungan_keluarga" class="form-select" required>
              <option value="">Pilih</option>
              <option value="Istri">Istri</option>
              <option value="Suami">Suami</option>
              <option value="Anak">Anak</option>
              <option value="Orang Tua">Orang Tua</option>
              <option value="Saudara">Saudara</option>
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Agama</label>
            <select name="agama" class="form-select">
              <option value="">Pilih</option>
              <option value="Islam">Islam</option>
              <option value="Kristen">Kristen</option>
              <option value="Katolik">Katolik</option>
              <option value="Hindu">Hindu</option>
              <option value="Buddha">Buddha</option>
              <option value="Konghucu">Konghucu</option>
            </select>
          </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
          <a href="data_keluarga.php" class="btn btn-secondary">Kembali</a>
          <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-paper-plane"></i> Ajukan Penambahan
          </button>
        </div>
      </form>
    </div>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
