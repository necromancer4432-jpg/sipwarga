<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/functions.php';
requireLogin();

$nik = $_SESSION['nik'];
$message = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password_lama = $_POST['password_lama'] ?? '';
    $password_baru = $_POST['password_baru'] ?? '';
    $password_konfirmasi = $_POST['password_konfirmasi'] ?? '';
    
    if(!empty($password_baru)) {
        // Verify old password
        $query = "SELECT password FROM warga WHERE nik = '$nik'";
        $result = mysqli_query($koneksi, $query);
        $user = mysqli_fetch_assoc($result);
        
        if(password_verify($password_lama, $user['password'])) {
            if($password_baru === $password_konfirmasi) {
                $password_hash = password_hash($password_baru, PASSWORD_BCRYPT);
                $update = "UPDATE warga SET password = '$password_hash' WHERE nik = '$nik'";
                if(mysqli_query($koneksi, $update)) {
                    $message = 'Password berhasil diubah!';
                }
            } else {
                $message = 'Konfirmasi password tidak cocok!';
            }
        } else {
            $message = 'Password lama salah!';
        }
    }
}
// ambil foto profil dari data_pribadi
$profil = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT foto FROM data_pribadi WHERE nik='$nik'")
) ?: [];


$query = "SELECT * FROM warga WHERE nik = '$nik'";
$result = mysqli_query($koneksi, $query);
$userData = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pengaturan - SipWarga</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../../assets/Css/pengaturan_warga.css">
  <link rel="stylesheet" href="../../assets/Css/warga.css">
  <style>
    .alert{padding:15px;margin-bottom:20px;border-radius:8px;background:#d4edda;color:#155724;display:<?php echo $message ? 'block' : 'none'; ?>; width: 100%; max-width: 800px;}
  </style>
</head>
<body>
<div class="container">
    <?php include '../../includes/sidebar_warga.php'; ?>

  <main class="content">
    <div class="settings-wrapper">
      <h1 class="page-title">Pengaturan</h1>
    
    <?php if($message): ?>
    <div class="alert"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="profile-card">
  <?php if(!empty($profil['foto'])): ?>
    <img src="../../uploads/<?=
      htmlspecialchars($profil['foto']) ?>?v=<?= time(); ?>"
      class="avatar">
  <?php else: ?>
    <div class="avatar" style="background:#fff;border:1px solid #ddd;"></div>
  <?php endif; ?>

  <div>
    <h3><?php echo htmlspecialchars($userData['username']); ?></h3>
    <p>NIK: <?php echo htmlspecialchars($userData['nik']); ?></p>
  </div>
</div>


    <div class="card">
      <h2>Ubah Kata Sandi</h2>
      <p class="desc">Pastikan kata sandi baru Anda aman dan mudah diingat. Jangan bagikan kata sandi Anda kepada siapa pun</p>

      <form class="password-form" method="POST">
        <div class="input-group">
          <label>Kata Sandi saat ini</label>
          <div class="input-icon">
            <input type="password" name="password_lama" placeholder="Masukan Kata Sandi saat ini" required>
            <i class="fa-solid fa-eye toggle-password"></i>
          </div>
        </div>

        <div class="input-group">
          <label>Kata Sandi Baru</label>
          <div class="input-icon">
            <input type="password" name="password_baru" placeholder="Masukan Kata Sandi Baru" required minlength="6">
            <i class="fa-solid fa-eye toggle-password"></i>
          </div>
        </div>

        <div class="input-group">
          <label>Konfirmasi Kata Sandi Baru</label>
          <div class="input-icon">
            <input type="password" name="password_konfirmasi" placeholder="Masukan ulang Kata Sandi Baru" required>
            <i class="fa-solid fa-eye toggle-password"></i>
          </div>
        </div>

        <div class="form-action">
          <button type="reset" class="custom-btn-cancel">
          <i class="fa fa-xmark"></i> Batal
          </button>

          <button type="submit" class="custom-btn-primary">
          <i class="fa fa-save"></i> Simpan perubahan
          </button>
        </div>
      </form>
    </div>
    </div>
  </main>
</div>
<script>
  //toggle eye
document.addEventListener('click', function (e) {
  if (e.target.classList.contains('toggle-password')) {

    const icon = e.target;
    const input = icon.previousElementSibling;

    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  }
});
</script>

</body>
</html>
