<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
requireAdmin();

/* ===============================
   HAPUS RIWAYAT (HANYA APPROVED)
================================ */
if (isset($_GET['aksi'], $_GET['id']) && $_GET['aksi'] === 'hapus') {

    $id = (int) $_GET['id'];

    mysqli_query($koneksi, "
        DELETE FROM pengajuan_surat
        WHERE id_pengajuan = $id
          AND status = 'approved'
    ");

    header("Location: riwayat_pengajuan_warga.php");
    exit;
}

/* ===============================
   APPROVE / REJECT
================================ */
if (isset($_GET['aksi'], $_GET['id'])) {

    $id = (int) $_GET['id'];

    if ($_GET['aksi'] === 'approve') {

        $query = "
            UPDATE pengajuan_surat
            SET status='approved',
                tanggal_proses=NOW()
            WHERE id_pengajuan=$id
              AND status='pending'
        ";

        mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));
    }

    if ($_GET['aksi'] === 'reject') {

        $query = "
            UPDATE pengajuan_surat
            SET status='rejected',
                tanggal_proses=NOW()
            WHERE id_pengajuan=$id
              AND status='pending'
        ";

        mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));
    }

    header("Location: riwayat_pengajuan_warga.php");
    exit;
}
/* ===============================
   DATA PENGAJUAN SURAT
================================ */
$data = mysqli_query($koneksi, "
    SELECT ps.*, w.username
    FROM pengajuan_surat ps
    LEFT JOIN warga w ON ps.nik = w.nik
    ORDER BY ps.tanggal_pengajuan DESC
");

if (!$data) {
    die(mysqli_error($koneksi));
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pengajuan Surat Warga</title>
<link rel="stylesheet" href="../../assets/Css/ketua_rt.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
table{width:100%;border-collapse:collapse;background:#fff;margin-top:20px}
th,td{padding:12px;border-bottom:1px solid #ddd;text-align:center}
th{background:#f8f9fa}

.badge{
  padding:5px 12px;
  border-radius:20px;
  font-size:12px;
  color:#fff;
  font-weight:600;
}
.badge-pending{background:#ffc107;color:#000}
.badge-approved{background:#007bff}
.badge-rejected{background:#dc3545}

.btn-sm{
  padding:6px 10px;
  border-radius:4px;
  font-size:12px;
  color:#fff;
  text-decoration:none;
}
.btn-success{background:#28a745}
.btn-danger{background:#dc3545}
</style>
</head>

<body>
<div class="container">

<!-- SIDEBAR -->
  <?php include '../../includes/sidebar_admin.php'; ?>


<main class="content">
<h1>Pengajuan Surat Warga</h1>

<?php if (mysqli_num_rows($data) === 0): ?>
  <p style="text-align:center;color:#777">Belum ada pengajuan surat</p>
<?php else: ?>

<table>
<thead>
<tr>
  <th>No</th>
  <th>NIK</th>
  <th>Nama</th>
  <th>Jenis Surat</th>
  <th>Tanggal Pengajuan</th>
  <th>Status</th>
  <th>Aksi</th>
</tr>
</thead>
<tbody>

<?php $no=1; while($r=mysqli_fetch_assoc($data)): ?>
<tr>
  <td><?= $no++; ?></td>
  <td><?= htmlspecialchars($r['nik']); ?></td>
  <td><?= htmlspecialchars($r['username'] ?? '-'); ?></td>
  <td><?= ucfirst($r['jenis_surat']); ?></td>
  <td><?= date('d M Y H:i', strtotime($r['tanggal_pengajuan'])); ?></td>
  <td>
    <?php
      $status = $r['status'];
      $class  = $status === 'approved' ? 'badge-approved'
              : ($status === 'rejected' ? 'badge-rejected' : 'badge-pending');
    ?>
    <span class="badge <?= $class; ?>">
      <?= strtoupper($status); ?>
    </span>
  </td>
 <td>
  <?php if ($status === 'pending'): ?>

    <a href="?aksi=approve&id=<?= $r['id_pengajuan']; ?>"
       class="btn-sm btn-success"
       onclick="return confirm('Setujui surat ini?')">✔</a>

    <a href="?aksi=reject&id=<?= $r['id_pengajuan']; ?>"
       class="btn-sm btn-danger"
       onclick="return confirm('Tolak surat ini?')">✖</a>

  <?php elseif ($status === 'approved'): ?>

    <a href="?aksi=hapus&id=<?= $r['id_pengajuan']; ?>"
       class="btn-sm btn-danger"
       onclick="return confirm('Hapus riwayat pengajuan ini?')">
       🗑️
    </a>

  <?php else: ?>
    -
  <?php endif; ?>
</td>
</tr>
<?php endwhile; ?>

</tbody>
</table>

<?php endif; ?>
</main>
</div>
</body>
</html>
