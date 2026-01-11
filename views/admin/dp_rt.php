<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
requireAdmin();

$nik = $_SESSION['nik'];

/* DATA ADMIN */
$q = mysqli_query($koneksi,"
    SELECT username, alamat, jenis_kelamin, agama, rt_rw, foto_profil
    FROM warga
    WHERE nik='$nik' AND role='admin'
    LIMIT 1
");
$admin = mysqli_fetch_assoc($q);

/* UPDATE */
if (isset($_POST['update'])) {

    $username = mysqli_real_escape_string($koneksi,$_POST['username']);
    $alamat   = mysqli_real_escape_string($koneksi,$_POST['alamat']);
    $jk       = mysqli_real_escape_string($koneksi,$_POST['jenis_kelamin']);
    $agama    = mysqli_real_escape_string($koneksi,$_POST['agama']);
    $rt_rw    = mysqli_real_escape_string($koneksi,$_POST['rt_rw']);

    $foto_sql = '';
    if (!empty($_FILES['foto_profil']['name'])) {
        $ext = strtolower(pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION));
        if (in_array($ext,['jpg','jpeg','png','webp'])) {

            if (!empty($admin['foto_profil']) && file_exists('../../uploads/'.$admin['foto_profil'])) {
                unlink('../../uploads/'.$admin['foto_profil']);
            }

            $foto = 'admin_'.time().'.'.$ext;
            move_uploaded_file($_FILES['foto_profil']['tmp_name'], '../../uploads/'.$foto);
            $foto_sql = ", foto_profil='$foto'";
        }
    }

    mysqli_query($koneksi,"
        UPDATE warga SET
            username='$username',
            alamat='$alamat',
            jenis_kelamin='$jk',
            agama='$agama',
            rt_rw='$rt_rw'
            $foto_sql
        WHERE nik='$nik' AND role='admin'
    ");

    echo "<script>alert('Perubahan berhasil disimpan');location.href='dp_rt.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Profil Admin</title>
<link rel="stylesheet" href="../../assets/Css/ketua_rt.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* SCOPED — TIDAK MERUSAK SIDEBAR */
.profile-box{
  max-width:820px;
  background:#fff;
  padding:30px;
  border-radius:10px;
  margin-top:20px;
}

.profile-top{
  display:flex;
  align-items:center;
  gap:25px;
  margin-bottom:25px;
}

.avatar{
  width:140px;
  height:140px;
  border-radius:50%;
  overflow:hidden;
  cursor:pointer;
  border:4px solid #eee;
}
.avatar img{
  width:100%;
  height:100%;
  object-fit:cover;
}

.profile-form{
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:18px;
}

.form-group{
  display:flex;
  flex-direction:column;
}
.form-group.full{
  grid-column:1 / -1;
}
label{
  font-weight:600;
  margin-bottom:6px;
}
input, textarea, select{
  padding:11px 13px;
  border-radius:6px;
  border:1px solid #ccc;
}

.btn-save{
  margin-top:25px;
  padding:12px;
  background:#007bff;
  color:#fff;
  border:none;
  border-radius:6px;
  cursor:pointer;
}
</style>
</head>

<body>
<div class="container">

<?php include '../../includes/sidebar_admin.php'; ?>

<main class="content">
<h1>Profil Admin</h1>

<div class="profile-box">
<form method="POST" enctype="multipart/form-data">

<!-- FOTO -->
<div class="profile-top">
  <div class="avatar" onclick="document.getElementById('foto').click()">
    <img id="preview"
         src="../../uploads/<?= $admin['foto_profil'] ?: 'default.png'; ?>">
  </div>

  <div>
    <h2><?= htmlspecialchars($admin['username']); ?></h2>
    <p>Administrator</p>
  </div>
</div>

<input type="file" name="foto_profil" id="foto" hidden onchange="previewFoto(this)">

<!-- FORM -->
<div class="profile-form">

  <div class="form-group full">
    <label>Nama</label>
    <input type="text" name="username" value="<?= htmlspecialchars($admin['username']); ?>" required>
  </div>

  <div class="form-group full">
    <label>Alamat</label>
    <textarea name="alamat"><?= htmlspecialchars($admin['alamat']); ?></textarea>
  </div>

  <div class="form-group">
    <label>Jenis Kelamin</label>
    <select name="jenis_kelamin">
      <option value="">- Pilih -</option>
      <option value="Laki-laki" <?= $admin['jenis_kelamin']=='Laki-laki'?'selected':''; ?>>Laki-laki</option>
      <option value="Perempuan" <?= $admin['jenis_kelamin']=='Perempuan'?'selected':''; ?>>Perempuan</option>
    </select>
  </div>

  <div class="form-group">
    <label>Agama</label>
    <select name="agama">
      <?php foreach(['Islam','Kristen','Katolik','Hindu','Buddha'] as $a): ?>
        <option value="<?= $a; ?>" <?= $admin['agama']==$a?'selected':''; ?>><?= $a; ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div class="form-group full">
    <label>RT / RW</label>
    <input type="text" name="rt_rw" value="<?= htmlspecialchars($admin['rt_rw']); ?>">
  </div>

</div>

<button class="btn-save" name="update">Update Perubahan</button>
</form>
</div>

</main>
</div>

<script>
function previewFoto(input){
  if(input.files && input.files[0]){
    const reader = new FileReader();
    reader.onload = e => document.getElementById('preview').src = e.target.result;
    reader.readAsDataURL(input.files[0]);
  }
}
</script>

</body>
</html>
