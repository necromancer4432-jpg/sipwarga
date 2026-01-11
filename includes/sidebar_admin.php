<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';

/* ===============================
   DEFAULT NILAI (ANTI ERROR)
================================ */
$total_riwayat = 0;

/* ===============================
   QUERY AMAN (CEK DULU)
================================ */
$sql = "
    SELECT COUNT(*) AS total 
    FROM riwayat_administrasi 
    WHERE keterangan = 'pending'
";

$result = mysqli_query($koneksi, $sql);

/* 
   👉 JIKA QUERY GAGAL
   sidebar tetap tampil
*/
if ($result instanceof mysqli_result) {
    $row = mysqli_fetch_assoc($result);
    $total_riwayat = (int)($row['total'] ?? 0);
}

/* ===============================
   ACTIVE MENU OTOMATIS
================================ */
$current = basename($_SERVER['PHP_SELF']);

function activeMenu($file)
{
    global $current;
    return $current === $file ? 'class="active"' : '';
}
?>

<aside class="sidebar">
  <h2 class="logo">SipWarga! Admin</h2>

  <div class="menu">
    <h3>Admin</h3>
    <ul>

      <li <?= activeMenu('dashboard_rt.php'); ?>>
        <i class="fa-solid fa-house"></i>
        <a href="dashboard_rt.php"> Dashboard</a>
      </li>

      <li <?= activeMenu('data_warga.php'); ?>>
        <i class="fa-solid fa-users"></i>
        <a href="data_warga.php"> Data Warga</a>
      </li>

      <li <?= activeMenu('verifikasi_akun.php'); ?>>
        <i class="fa-solid fa-user-check"></i>
        <a href="verifikasi_akun.php"> Verifikasi Akun</a>
      </li>

      <li <?= activeMenu('riwayat_pengajuan_warga.php'); ?>>
        <i class="fa-solid fa-clock-rotate-left"></i>
        <a href="riwayat_pengajuan_warga.php">
          Riwayat Pengajuan
          <?php if ($total_riwayat > 0): ?>
            <span class="badge"><?= $total_riwayat; ?></span>
          <?php endif; ?>
        </a>
      </li>

      <li <?= activeMenu('dp_rt.php'); ?>>
        <i class="fa-solid fa-user-tie"></i>
        <a href="dp_rt.php"> Profil</a>
      </li>

      <li>
        <i class="fa-solid fa-right-from-bracket"></i>
        <a href="#" onclick="confirmLogout(event)">Keluar</a>
      </li>

    </ul>
  </div>
</aside>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmLogout(e){
  e.preventDefault();

  Swal.fire({
    title: 'Yakin ingin keluar?',
    text: 'Anda akan logout dari sistem',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Ya, keluar',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = '../../logout.php';
    }
  });
}
</script>
