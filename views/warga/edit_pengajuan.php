<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
requireLogin();

$id  = $_GET['id'];
$nik = $_SESSION['nik'];

$q = mysqli_query($koneksi, "
  SELECT * FROM riwayat_administrasi
  WHERE id_riwayat='$id'
  AND nik='$nik'
  AND keterangan='pending'
");

if (mysqli_num_rows($q) == 0) {
  echo "<script>alert('Tidak bisa diedit');history.back()</script>";
  exit;
}

$r = mysqli_fetch_assoc($q);

if (isset($_POST['simpan'])) {
  $jenis = $_POST['jenis_kegiatan'];
  mysqli_query($koneksi, "
    UPDATE riwayat_administrasi
    SET jenis_kegiatan='$jenis'
    WHERE id_riwayat='$id'
  ");
  header("Location: riwayat_pengajuan.php");
  exit;
}
?>
