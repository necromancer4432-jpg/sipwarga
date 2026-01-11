<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
requireAdmin();

/* ================= VERIFIKASI AKUN WARGA ================= */
if (isset($_GET['aksi_user'], $_GET['nik'])) {

    $nik = mysqli_real_escape_string($koneksi, $_GET['nik']);

    if ($_GET['aksi_user'] === 'approve') {
        mysqli_query($koneksi, "
            UPDATE warga
            SET status = 'active'
            WHERE nik = '$nik' AND status = 'pending'
        ");

        $_SESSION['flash'] = '✅ Akun warga berhasil diaktifkan';
        $_SESSION['flash_type'] = 'success';
    }

    if ($_GET['aksi_user'] === 'reject') {
        mysqli_query($koneksi, "
            UPDATE warga
            SET status = 'inactive'
            WHERE nik = '$nik' AND status = 'pending'
        ");

        $_SESSION['flash'] = '❌ Akun warga ditolak';
        $_SESSION['flash_type'] = 'danger';
    }

    header("Location: verifikasi_akun.php");
    exit;
}

/* ===== DATA AKUN WARGA PENDING ===== */
$resUser = mysqli_query($koneksi, "
    SELECT nik, username, alamat
    FROM warga
    WHERE role = 'warga'
      AND status = 'pending'
    ORDER BY nik DESC
");

/* ================= PROSES APPROVE / REJECT ================= */
if (isset($_GET['action_keluarga'], $_GET['id_pengajuan'])) {

    $id_pengajuan = (int) $_GET['id_pengajuan'];

    if ($_GET['action_keluarga'] === 'approve') {

        $q = mysqli_query($koneksi, "
            SELECT *
            FROM pengajuan_keluarga
            WHERE id_pengajuan = $id_pengajuan
        ");
        $p = mysqli_fetch_assoc($q);

        // cek duplikasi
        $cek = mysqli_query($koneksi, "
            SELECT 1
            FROM data_keluarga
            WHERE nik_anggota = '{$p['nik_anggota']}'
        ");

        if (mysqli_num_rows($cek) > 0) {

            mysqli_query($koneksi, "
                UPDATE pengajuan_keluarga
                SET status = 'rejected'
                WHERE id_pengajuan = $id_pengajuan
            ");

            $_SESSION['flash'] = '❌ Gagal approve: NIK sudah terdaftar';
            $_SESSION['flash_type'] = 'danger';

        } else {

            mysqli_query($koneksi, "
                INSERT INTO data_keluarga (
                    nik_kepala,
                    no_kk,
                    nik_anggota,
                    nama_anggota,
                    hubungan_keluarga,
                    tempat_lahir,
                    tanggal_lahir,
                    jenis_kelamin,
                    agama,
                    created_at
                ) VALUES (
                    '{$p['nik_kepala']}',
                    '{$p['nik_kepala']}',
                    '{$p['nik_anggota']}',
                    '{$p['nama_anggota']}',
                    '{$p['hubungan_keluarga']}',
                    '{$p['tempat_lahir']}',
                    '{$p['tanggal_lahir']}',
                    '{$p['jenis_kelamin']}',
                    '{$p['agama']}',
                    NOW()
                )
            ");

            mysqli_query($koneksi, "
                UPDATE pengajuan_keluarga
                SET status = 'approved'
                WHERE id_pengajuan = $id_pengajuan
            ");

            $_SESSION['flash'] = '✅ Pengajuan keluarga berhasil disetujui';
            $_SESSION['flash_type'] = 'success';
        }
    }

    if ($_GET['action_keluarga'] === 'reject') {

        mysqli_query($koneksi, "
            UPDATE pengajuan_keluarga
            SET status = 'rejected'
            WHERE id_pengajuan = $id_pengajuan
        ");

        $_SESSION['flash'] = '❌ Pengajuan keluarga ditolak';
        $_SESSION['flash_type'] = 'danger';
    }

    header("Location: verifikasi_akun.php");
    exit;
}

/* ================= AMBIL DATA PENGAJUAN ================= */
$resPengajuan = mysqli_query($koneksi, "
    SELECT *
    FROM pengajuan_keluarga
    WHERE status = 'pending'
    ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Verifikasi Akun</title>
<link rel="stylesheet" href="../../assets/Css/ketua_rt.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
table{width:100%;border-collapse:collapse;background:#fff;margin-top:20px}
th,td{padding:12px;border-bottom:1px solid #ddd}
th{background:#f5f5f5}
.btn-sm{padding:6px 12px;border-radius:4px;font-size:12px;text-decoration:none}
.btn-success{background:#28a745;color:#fff}
.btn-danger{background:#dc3545;color:#fff}
.text-center{text-align:center}
</style>
</head>

<body>
<div class="container">

  <!-- SIDEBAR -->
  <?php include '../../includes/sidebar_admin.php'; ?>
<main class="content">

<!-- ================= VERIFIKASI AKUN WARGA ================= -->
<h1 class="text-center">Verifikasi Akun Warga</h1>

<?php if (mysqli_num_rows($resUser) === 0): ?>
    <div class="empty-center">
        Tidak ada akun warga pending
    </div>
<?php else: ?>

<table>
<thead>
<tr>
  <th>No</th>
  <th>NIK</th>
  <th>Nama</th>
  <th>Alamat</th>
  <th class="text-center">Aksi</th>
</tr>
</thead>
<tbody>
<?php $no=1; while($u=mysqli_fetch_assoc($resUser)): ?>
<tr>
  <td><?= $no++; ?></td>
  <td><?= htmlspecialchars($u['nik']); ?></td>
  <td><?= htmlspecialchars($u['username']); ?></td>
  <td><?= htmlspecialchars($u['alamat']); ?></td>
  <td class="text-center">
    <a href="?aksi_user=approve&nik=<?= $u['nik']; ?>" class="btn-sm btn-success">✔ Setujui</a>
    <a href="?aksi_user=reject&nik=<?= $u['nik']; ?>" class="btn-sm btn-danger">✖ Tolak</a>
  </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<?php endif; ?>

<!-- ================= PEMISAH ================= -->
<div class="section-divider"></div>

<!-- ================= VERIFIKASI PENGAJUAN KELUARGA ================= -->
<h1 class="text-center" style="margin-top:40px;">Verifikasi Pengajuan Keluarga</h1>

<?php if (!$resPengajuan || mysqli_num_rows($resPengajuan) === 0): ?>
    <div class="empty-center">
        Tidak ada pengajuan keluarga
    </div>
<?php else: ?>

<table>
<thead>
<tr>
  <th>No</th>
  <th>NIK Kepala</th>
  <th>Nama Anggota</th>
  <th>Hubungan</th>
  <th>Jenis Kelamin</th>
  <th class="text-center">Aksi</th>
</tr>
</thead>
<tbody>
<?php $no=1; while($p=mysqli_fetch_assoc($resPengajuan)): ?>
<tr>
  <td><?= $no++; ?></td>
  <td><?= htmlspecialchars($p['nik_kepala']); ?></td>
  <td><?= htmlspecialchars($p['nama_anggota']); ?></td>
  <td><?= htmlspecialchars($p['hubungan_keluarga']); ?></td>
  <td><?= htmlspecialchars($p['jenis_kelamin']); ?></td>
  <td class="text-center">
    <a href="?action_keluarga=approve&id_pengajuan=<?= $p['id_pengajuan']; ?>" class="btn-sm btn-success">✔ Setujui</a>
    <a href="?action_keluarga=reject&id_pengajuan=<?= $p['id_pengajuan']; ?>" class="btn-sm btn-danger">✖ Tolak</a>
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
