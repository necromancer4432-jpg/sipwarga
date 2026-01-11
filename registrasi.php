<?php
session_start();
require_once "includes/config.php";

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    $nik      = trim($_POST['nik'] ?? '');
    $alamat   = trim($_POST['alamat'] ?? '');

    /* ================= VALIDASI ================= */
    if ($username === '' || $password === '' || $nik === '' || $alamat === '') {
        $error = 'Semua field wajib diisi.';
    } elseif (!ctype_digit($nik)) {
        $error = 'NIK harus berupa angka.';
    } elseif (strlen($nik) !== 16) {
        $error = 'NIK harus 16 digit.';
    } elseif ($password !== $confirm) {
        $error = 'Password dan konfirmasi tidak cocok.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } else {

        /* ================= ESCAPE ================= */
        $username_e = mysqli_real_escape_string($koneksi, $username);
        $nik_e      = mysqli_real_escape_string($koneksi, $nik);
        $alamat_e   = mysqli_real_escape_string($koneksi, $alamat);
        $pass_hash  = password_hash($password, PASSWORD_DEFAULT);

        /* ================= CEK DUPLIKAT NIK ================= */
        $cek = mysqli_query(
            $koneksi,
            "SELECT 1 FROM warga WHERE nik = '$nik_e' LIMIT 1"
        );

        if (mysqli_num_rows($cek) > 0) {
            $error = 'NIK sudah terdaftar!';
        } else {

            /* ================= INSERT (WAJIB EXPLICIT) ================= */
            $sql = "
                INSERT INTO warga
                    (nik, username, password, alamat, umur, role, status)
                VALUES
                    ('$nik_e', '$username_e', '$pass_hash', '$alamat_e', 0, 'warga', 'pending')
            ";

            if (mysqli_query($koneksi, $sql)) {

                $_SESSION['register_success'] = true;
                header("Location: login.php");
                exit;

            } else {
                $error = 'Gagal menyimpan data: ' . mysqli_error($koneksi);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrasi Akun - SipWarga!</title>
  <link rel="stylesheet" href="assets/Css/Regist.css">
</head>

<body>
  <div class="container">
    <div class="image-box">
      <img src="assets/images/pana.png" alt="Ilustrasi Registrasi">
    </div>

    <div class="form-box">
      <h2>Registrasi Akun</h2>

      <?php if ($error): ?>
        <div class="alert alert-error">
          <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>



      <form method="POST">
        <div class="form-group">
          <label for="username">Nama Lengkap</label>
          <input type="text" name="username" id="username" placeholder="Masukan Nama" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required />
        </div>
        <div class="form-group">
          <label for="nik">NIK (16 digit)</label>
          <input type="text" name="nik" id="nik" placeholder="Masukan NIK" maxlength="16" value="<?php echo isset($_POST['nik']) ? htmlspecialchars($_POST['nik']) : ''; ?>" required />
        </div>
        <div class="form-group">
          <label for="alamat">Alamat</label>
          <input type="text" name="alamat" id="alamat" placeholder="Masukan Alamat" value="<?php echo isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : ''; ?>" required />
        </div>
        <div class="form-group">
          <label for="password">Kata Sandi</label>
          <input type="password" name="password" id="password" placeholder="Minimal 6 karakter" required />
        </div>
        <div class="form-group">
          <label for="confirm_password">Konfirmasi Kata Sandi</label>
          <input type="password" name="confirm_password" id="confirm_password" placeholder="Ulangi Kata Sandi" required />
        </div>
        <div class="auth-footer">
          <p class="login-text">
            Sudah punya akun?
            <a href="login.php" class="next-link">Masuk</a>
          </p>
          <button type="submit" class="btn-daftar">Daftar</button>
        </div>

      </form>

    </div>
  </div>
</body>

</html>