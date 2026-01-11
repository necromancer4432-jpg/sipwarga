<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
requireLogin();

$nik = $_SESSION['nik'];
$username = $_SESSION['username'];

// data kepala keluarga
$qKepala = "
    SELECT dp.*, w.alamat 
    FROM data_pribadi dp
    LEFT JOIN warga w ON dp.nik = w.nik
    WHERE dp.nik = '$nik'
";
$resKepala = mysqli_query($koneksi, $qKepala);
$kepalaKeluarga = mysqli_fetch_assoc($resKepala);

// anggota keluarga (HASIL APPROVE)
$qAnggota = "
    SELECT 
        nama_anggota,
        nik_anggota,
        hubungan_keluarga,
        jenis_kelamin,
        agama
    FROM data_keluarga
    WHERE nik_kepala = '$nik'
    ORDER BY created_at ASC
";
$resAnggota = mysqli_query($koneksi, $qAnggota);

if (!$resAnggota) {
    die('Query anggota keluarga error: ' . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>SipWarga - Data Keluarga</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="../../assets/Css/data_keluarga.css">
<link rel="stylesheet" href="../../assets/Css/warga.css">
</head>
<body>
<div class="container">
    <?php include '../../includes/sidebar_warga.php'; ?>

  <main class="content">
    <div class="header">
      <h1>Data Keluarga</h1>
      <div class="header-btn">
        <button class="btn-primary">
          <i class="fa-solid fa-plus"></i> <a href="tambah_data_keluarga.php" style="color:inherit;text-decoration:none"> Tambah Anggota keluarga</a>
        </button>
      </div>
    </div>

    <div class="card">
      <div class="form-row">
        <div class="form-group">
          <label>Kepala Keluarga</label>
          <input type="text" value="<?php echo htmlspecialchars($username); ?>" readonly style="background:#f5f5f5">
        </div>
        <div class="form-group">
          <label>No Kartu Keluarga</label>
          <input type="text" value="<?php echo htmlspecialchars($nik); ?>" readonly style="background:#f5f5f5">
        </div>
      </div>

    <h4 class="section-title" style="margin-top:30px">Anggota Keluarga</h4>

<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>NIK</th>
      <th>Hubungan</th>
      <th>Jenis Kelamin</th>
      <th>Agama</th>
    </tr>
  </thead>
  <tbody>

<?php if (mysqli_num_rows($resAnggota) > 0): ?>
<?php $no = 1; while ($a = mysqli_fetch_assoc($resAnggota)): ?>
<tr>
  <td><?= $no++ ?></td>
  <td><?= htmlspecialchars($a['nama_anggota']) ?></td>
  <td><?= htmlspecialchars($a['nik_anggota']) ?></td>
  <td><?= htmlspecialchars($a['hubungan_keluarga']) ?></td>
  <td><?= htmlspecialchars($a['jenis_kelamin']) ?></td>
  <td><?= htmlspecialchars($a['agama']) ?></td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
  <td colspan="6" style="text-align:center;color:#999">
    Belum ada anggota keluarga
  </td>
</tr>
<?php endif; ?>

  </tbody>
</table>
 </div>
 </main>
</div>
</body>
</html>
