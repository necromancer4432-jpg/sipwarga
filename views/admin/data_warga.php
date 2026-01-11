<?php
/* =========================================
   SESSION & KEAMANAN
========================================= */
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
requireAdmin();

/* =========================================
   FILTER INPUT
========================================= */
$agama      = $_GET['agama'] ?? '';
$jk         = $_GET['jk'] ?? '';
$nik_kepala = $_GET['nik_kepala'] ?? '';
$export     = $_GET['export'] ?? '';

$isExport = ($export === 'excel');

/* =========================================
   QUERY DINAMIS (AMAN & BENAR)
========================================= */
$where = ["w.role='warga'"]; // hanya kepala keluarga

if ($agama !== '') {
    $agama = mysqli_real_escape_string($koneksi, $agama);
    $where[] = "COALESCE(k.agama, w.agama) = '$agama'";
}

if ($jk !== '') {
    $jk = mysqli_real_escape_string($koneksi, $jk);
    $where[] = "COALESCE(k.jenis_kelamin, w.jenis_kelamin) = '$jk'";
}

if ($nik_kepala !== '') {
    $nik_kepala = mysqli_real_escape_string($koneksi, $nik_kepala);
    $where[] = "w.nik = '$nik_kepala'";
}

$whereSQL = implode(' AND ', $where);

/* =========================================
   QUERY DATA UTAMA
========================================= */
$query = "

-- KEPALA KELUARGA (DITAMPILKAN SEBAGAI ANGGOTA)
SELECT
  w.nik AS nik_kepala,
  w.username AS nama_kepala,

  w.username AS nama_anggota,
  w.nik AS nik_anggota,
  'Kepala Keluarga' AS hubungan_keluarga,

  w.jenis_kelamin,
  w.agama

FROM warga w
WHERE $whereSQL

UNION ALL

-- ANGGOTA KELUARGA
SELECT
  w.nik AS nik_kepala,
  w.username AS nama_kepala,

  k.nama_anggota,
  k.nik_anggota,
  k.hubungan_keluarga,

  k.jenis_kelamin,
  k.agama

FROM warga w
JOIN data_keluarga k 
  ON k.nik_kepala = w.nik

WHERE $whereSQL

ORDER BY nik_kepala ASC
";


$data = mysqli_query($koneksi, $query);

/* =========================================
   EXPORT EXCEL (HENTIKAN HTML)
========================================= */
if ($isExport) {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=data_warga.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
}
?>

<?php if (!$isExport): ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Warga</title>

<link rel="stylesheet" href="../../assets/Css/ketua_rt.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
.filter-box{background:#fff;padding:15px;border-radius:8px;margin-bottom:15px}
.filter-box form{display:flex;gap:10px;flex-wrap:wrap}
.filter-box input,.filter-box select{padding:8px}
.btn{padding:8px 12px;border-radius:4px;border:none;cursor:pointer}
.btn-primary{background:#007bff;color:#fff}
.btn-success{background:#28a745;color:#fff}
table{width:100%;border-collapse:collapse;background:#fff}
th,td{padding:10px;border:1px solid #ddd}
th{background:#f2f2f2}
</style>
</head>

<body>
<div class="container">

<?php include '../../includes/sidebar_admin.php'; ?>

<main class="content">
<h1>Data Warga</h1>

<!-- FILTER -->
<div class="filter-box">
<form method="GET">

  <input type="text" name="nik_kepala" placeholder="NIK Kepala"
         value="<?= htmlspecialchars($nik_kepala); ?>">

  <select name="agama">
    <option value="">-- Agama --</option>
    <?php foreach(['Islam','Kristen','Katolik','Hindu','Buddha'] as $a): ?>
      <option value="<?= $a; ?>" <?= $agama==$a?'selected':''; ?>>
        <?= $a; ?>
      </option>
    <?php endforeach; ?>
  </select>

  <select name="jk">
    <option value="">-- Jenis Kelamin --</option>
    <option value="Laki-laki" <?= $jk=='Laki-laki'?'selected':''; ?>>Laki-laki</option>
    <option value="Perempuan" <?= $jk=='Perempuan'?'selected':''; ?>>Perempuan</option>
  </select>

  <button class="btn btn-primary">Filter</button>

  <a href="?<?= http_build_query($_GET + ['export'=>'excel']); ?>"
     class="btn btn-success">
     Export Excel
  </a>

</form>
</div>
<?php endif; ?>

<!-- TABLE (DIPAKAI WEB & EXCEL) -->
<table>
<thead>
<tr>
  <th>No</th>
  <th>NIK Kepala</th>
  <th>Nama Kepala</th>
  <th>Nama Anggota</th>
  <th>NIK Anggota</th>
  <th>Hubungan</th>
  <th>Jenis Kelamin</th>
  <th>Agama</th>
</tr>
</thead>
<tbody>

<?php
if (!$data || mysqli_num_rows($data) == 0):
?>
<tr>
  <td colspan="8" align="center">Data tidak ditemukan</td>
</tr>
<?php
else:
$no = 1;
while ($r = mysqli_fetch_assoc($data)):
?>
<tr>
  <td><?= $no++; ?></td>
  <td><?= $r['nik_kepala']; ?></td>
  <td><?= htmlspecialchars($r['nama_kepala']); ?></td>
  <td><?= htmlspecialchars($r['nama_anggota'] ?? '-'); ?></td>
  <td><?= htmlspecialchars($r['nik_anggota'] ?? '-'); ?></td>
  <td><?= htmlspecialchars($r['hubungan_keluarga'] ?? '-'); ?></td>
  <td><?= htmlspecialchars($r['jenis_kelamin'] ?? '-'); ?></td>
  <td><?= htmlspecialchars($r['agama'] ?? '-'); ?></td>
</tr>
<?php endwhile; endif; ?>

</tbody>
</table>

<?php if (!$isExport): ?>
</main>
</div>
</body>
</html>
<?php endif; ?>
