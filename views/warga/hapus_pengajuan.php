<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
requireLogin();

$id  = $_GET['id'];
$nik = $_SESSION['nik'];

mysqli_query($koneksi, "
  DELETE FROM riwayat_administrasi
  WHERE id_riwayat='$id'
  AND nik='$nik'
  AND keterangan='pending'
");

header("Location: riwayat_pengajuan.php");
exit;
