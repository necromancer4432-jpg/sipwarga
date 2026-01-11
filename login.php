<?php
session_start();
require_once 'includes/config.php';

$error = '';
$alert = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nik  = trim($_POST['nik'] ?? '');
    $pass = $_POST['password'] ?? '';

    /* ================= VALIDASI ================= */
    if ($nik === '' || $pass === '') {
        $error = 'Masukkan NIK dan password.';
    } elseif (!ctype_digit($nik)) {
        $error = 'NIK harus berupa angka.';
    } else {

        /* ================= PREPARED STATEMENT ================= */
        $stmt = mysqli_prepare($koneksi, "
            SELECT id_user, nik, password, role, status, username
            FROM warga
            WHERE nik = ?
            LIMIT 1
        ");

        if (!$stmt) {
            $error = 'Terjadi kesalahan sistem.';
        } else {

            mysqli_stmt_bind_param($stmt, "s", $nik);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result(
                $stmt,
                $id_user,
                $db_nik,
                $db_pass_hash,
                $db_role,
                $db_status,
                $db_username
            );

            if (mysqli_stmt_fetch($stmt)) {

                /* ================= CEK PASSWORD ================= */
                if (!password_verify($pass, $db_pass_hash)) {
                    $error = 'NIK atau password salah.';
                }

                /* ================= BLOKIR NON-ACTIVE ================= */
                elseif ($db_status !== 'active') {

                    if ($db_status === 'pending') {
                        $alert = 'pending';
                    } else {
                        $alert = 'inactive';
                    }
                }

                /* ================= LOGIN BERHASIL ================= */
                else {

                    session_regenerate_id(true);

                    $_SESSION['id_user']  = $id_user;
                    $_SESSION['nik']      = $db_nik;
                    $_SESSION['role']     = $db_role;
                    $_SESSION['status']   = $db_status;
                    $_SESSION['username'] = $db_username;

                    if ($db_role === 'admin') {
                        header("Location: views/admin/dashboard_rt.php");
                    } else {
                        header("Location: views/warga/dashboard_warga.php");
                    }
                    exit;
                }

            } else {
                $error = 'NIK atau password salah.';
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login - SipWarga!</title>
<link rel="stylesheet" href="assets/Css/Login.css">
</head>
<body>

<div class="container">

    <div class="form-box">
        <h2>Masuk</h2>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>NIK</label>
                <input type="text" name="nik" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit">Masuk</button>
        </form>

        <p>Belum punya akun? <a href="registrasi.php">Daftar</a></p>
    </div>

</div>

<?php if ($alert === 'pending'): ?>
<script>
alert("⏳ Akun Anda masih menunggu verifikasi admin.");
</script>
<?php elseif ($alert === 'inactive'): ?>
<script>
alert("❌ Akun Anda dinonaktifkan.");
</script>
<?php endif; ?>

</body>
</html>
