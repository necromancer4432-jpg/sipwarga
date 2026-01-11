<?php
session_start();

/* ================= CONFIG & SECURITY ================= */
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
requireLogin();

/* ================= SESSION DATA ================= */
$nik      = $_SESSION['nik'];
$username = $_SESSION['username'] ?? 'User';

$message = '';
$message_type = '';

/* ================= LOAD DATA PRIBADI ================= */
$qPribadi = mysqli_query($koneksi, "SELECT * FROM data_pribadi WHERE nik='$nik'");
$dataPribadi = mysqli_fetch_assoc($qPribadi) ?: [];

/* ================= HANDLE FORM SUBMIT ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ===== FOTO DEFAULT ===== */
    $fotoName = $dataPribadi['foto'] ?? '';

    /* ================= UPLOAD FOTO ================= */
    if (!empty($_FILES['foto']['name'])) {

        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];

        if (in_array($ext, $allowed)) {

            // folder upload
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/sipwarga/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // nama file unik
            $fotoName = $nik . '_' . time() . '.' . $ext;
            move_uploaded_file($_FILES['foto']['tmp_name'], $uploadDir . $fotoName);
        }
    }

    /* ================= AMBIL INPUT ================= */
    $pekerjaan     = clean($_POST['pekerjaan'] ?? '');
    $tanggal_lahir = clean($_POST['tanggal_lahir'] ?? '');
    $jenis_kelamin = clean($_POST['jenis_kelamin'] ?? '');
    $agama         = clean($_POST['agama'] ?? '');

    /* ================= CEK DATA_PRIBADI ================= */
    $cek = mysqli_query($koneksi, "SELECT nik FROM data_pribadi WHERE nik='$nik'");

    if (mysqli_num_rows($cek)) {

        /* ===== UPDATE DATA_PRIBADI ===== */
        mysqli_query($koneksi, "
            UPDATE data_pribadi SET
                pekerjaan='$pekerjaan',
                tanggal_lahir='$tanggal_lahir',
                jenis_kelamin='$jenis_kelamin',
                agama='$agama',
                foto='$fotoName'
            WHERE nik='$nik'
        ");

        $message = 'Profil berhasil diperbarui';

    } else {

        /* ===== INSERT DATA_PRIBADI ===== */
        mysqli_query($koneksi, "
            INSERT INTO data_pribadi
            (nik, pekerjaan, tanggal_lahir, jenis_kelamin, agama, foto)
            VALUES
            ('$nik','$pekerjaan','$tanggal_lahir','$jenis_kelamin','$agama','$fotoName')
        ");

        $message = 'Profil berhasil disimpan';
    }

    /* ================= SINKRON KE TABEL WARGA =================
       PENTING!!
       Admin, filter, export baca data dari tabel warga
    */
    mysqli_query($koneksi, "
        UPDATE warga SET
            jenis_kelamin='$jenis_kelamin',
            agama='$agama'
        WHERE nik='$nik'
    ");

    $message_type = 'success';

    /* ================= RELOAD DATA ================= */
    $qPribadi = mysqli_query($koneksi, "SELECT * FROM data_pribadi WHERE nik='$nik'");
    $dataPribadi = mysqli_fetch_assoc($qPribadi) ?: [];
}

/* ================= LOAD DATA WARGA ================= */
$qWarga = mysqli_query($koneksi, "SELECT * FROM warga WHERE nik='$nik'");
$warga = mysqli_fetch_assoc($qWarga) ?: [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>SipWarga! - Profil</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../../assets/Css/data_pribadi.css">
  <link rel="stylesheet" href="../../assets/Css/warga.css">

  <style>
    .alert{padding:15px;margin-bottom:20px;border-radius:8px}
    .alert-success{background:#d4edda;color:#155724}
    .alert-error{background:#f8d7da;color:#721c24}

    .avatar{
      width:80px;
      height:80px;
      border-radius:50%;
      object-fit:cover;
      cursor:pointer;
    }
  </style>
</head>

<body>
<div class="container">
<?php include '../../includes/sidebar_warga.php'; ?>

<main class="content">
<h1>Profil</h1>

<?php if($message): ?>
<div class="alert alert-<?php echo $message_type; ?>">
  <?php echo htmlspecialchars($message); ?>
</div>
<?php endif; ?>
<form id="formProfil" method="POST" enctype="multipart/form-data">

<div class="profile-header">
  <div class="profile-info">
    <label for="foto" title="Ganti foto profil">
      <?php if(!empty($dataPribadi['foto'])): ?>
        <img src="../../uploads/<?=
          htmlspecialchars($dataPribadi['foto']) ?>?v=<?= time(); ?>"
          class="avatar">
      <?php else: ?>
        <div class="avatar" style="background:#fff;border:1px solid #ddd;"></div>
      <?php endif; ?>
    </label>

    <input type="file" name="foto" id="foto" accept="image/*" hidden>

    <div>
      <h3><?= htmlspecialchars($username); ?></h3>
      <p>NIK: <?= htmlspecialchars($nik); ?></p>
    </div>
  </div>
</div>

  <div class="form-group">
    <label>Alamat</label>
    <input type="text" value="<?php echo htmlspecialchars($warga['alamat'] ?? ''); ?>" readonly>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label>Tanggal Lahir</label>
      <input type="date" name="tanggal_lahir"
             value="<?php echo htmlspecialchars($dataPribadi['tanggal_lahir'] ?? ''); ?>">
    </div>

    <div class="form-group">
      <label>Jenis Kelamin</label>
      <select name="jenis_kelamin">
        <option value="">Pilih</option>
        <option value="Laki-laki" <?= ($dataPribadi['jenis_kelamin'] ?? '')=='Laki-laki'?'selected':'' ?>>Laki-laki</option>
        <option value="Perempuan" <?= ($dataPribadi['jenis_kelamin'] ?? '')=='Perempuan'?'selected':'' ?>>Perempuan</option>
      </select>
    </div>
  </div>

  <div class="form-row">
    <div class="form-group">
      <label>Agama</label>
      <select name="agama">
        <?php
        $agamaList=['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'];
        foreach($agamaList as $ag){
          $sel = ($dataPribadi['agama'] ?? '')==$ag?'selected':'';
          echo "<option value='$ag' $sel>$ag</option>";
        }
        ?>
      </select>
    </div>

    <div class="form-group">
      <label>Pekerjaan</label>
      <input type="text" name="pekerjaan"
             value="<?php echo htmlspecialchars($dataPribadi['pekerjaan'] ?? ''); ?>">
    </div>
  </div>

  <div class="form-actions">
    <button type="submit" class="btn-save">
      <i class="fa fa-save"></i> Simpan
    </button>
  </div>

</form>
</div>

</main>
</div>
<script>
document.getElementById('foto').addEventListener('change', function () {
  if (this.files.length > 0) {
    document.getElementById('formProfil').submit();
  }
});
</script>
</body>
</html>
