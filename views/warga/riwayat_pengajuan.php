<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
requireLogin();

$nik = $_SESSION['nik'];

/* ===============================
   AJUKAN SURAT
================================ */
if (isset($_POST['ajukan'])) {
 


    $jenis = mysqli_real_escape_string($koneksi, $_POST['jenis_surat']);

    // cek pending sejenis
    $cek = mysqli_query($koneksi, "
        SELECT 1 FROM pengajuan_surat
        WHERE nik='$nik'
        AND jenis_surat='$jenis'
        AND status='pending'
    ");

    if ($cek && mysqli_num_rows($cek) > 0) {
        echo "<script>
            alert('Masih ada pengajuan surat yang pending');
            location.href='riwayat_pengajuan.php';
        </script>";
        exit;
    }

    mysqli_query($koneksi, "
        INSERT INTO pengajuan_surat (nik, jenis_surat)
        VALUES ('$nik','$jenis')
    ");

    header("Location: riwayat_pengajuan.php");
    exit;
}

/* ===============================
   BATALKAN (HANYA PENDING)
================================ */
if (isset($_GET['hapus'])) {

    $id = (int) $_GET['hapus'];

    mysqli_query($koneksi, "
        DELETE FROM pengajuan_surat
        WHERE id_pengajuan=$id
        AND nik='$nik'
        AND status='pending'
    ");

    header("Location: riwayat_pengajuan.php");
    exit;
}

/* ===============================
   DATA RIWAYAT
================================ */
$data = mysqli_query($koneksi, "
    SELECT *
    FROM pengajuan_surat
    WHERE nik='$nik'
    ORDER BY tanggal_pengajuan DESC
");

if (!$data) {
    die('Query error: ' . mysqli_error($koneksi));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Pengajuan - SipWarga</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="refresh" content="30">


<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="../../assets/Css/riwayat_pengajuan.css">
<link rel="stylesheet" href="../../assets/Css/warga.css">

<style>
.status.pending{background:#ffc107;color:#000}
.status.approved{background:#0d6efd;color:#fff}
.status.rejected{background:#dc3545;color:#fff}
</style>
</head>

<body>
<div class="container">

<?php include '../../includes/sidebar_warga.php'; ?>

<main class="content">
<div class="riwayat-scope">

<h1 class="page-title">Riwayat Pengajuan Surat</h1>

<?php if (mysqli_num_rows($data) === 0): ?>
  <div class="empty-state">
    <h4>Belum ada pengajuan surat</h4>
  </div>
<?php else: ?>

<div class="riwayat-card">
<?php while ($r = mysqli_fetch_assoc($data)): ?>
  <div class="riwayat-item">

    <div class="riwayat-header">
      <h5>Surat <?= ucfirst($r['jenis_surat']); ?></h5>

      <?php if ($r['status'] === 'pending'): ?>
        <a href="?hapus=<?= $r['id_pengajuan']; ?>"
           onclick="return confirm('Batalkan pengajuan ini?')"
           class="btn-cancel">
           Batalkan
        </a>
      <?php endif; ?>
    </div>

    <div class="riwayat-meta">
      <span class="time">
        <?= date('d M Y • H:i', strtotime($r['tanggal_pengajuan'])); ?>
      </span>

      <span class="status <?= $r['status']; ?>">
        <?= strtoupper($r['status']); ?>
      </span>
    </div>

  </div>
<?php endwhile; ?>
</div>
<?php endif; ?>

<!-- AJUKAN BARU -->
<div class="ajukan-card">
  <h5>Ajukan Surat Baru</h5>
  <form method="POST">
    <select name="jenis_surat" required>
      <option value="">-- Pilih Surat --</option>
      <option value="surat domisili">Surat Domisili</option>
      <option value="surat kelahiran">Surat Kelahiran</option>
      <option value="surat kematian">Surat Kematian</option>
    </select>
    <button name="ajukan">
      <i class="fa fa-paper-plane"></i> Ajukan
    </button>
  </form>
</div>

</div>
</main>
</div>
</body>
</html>
