<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';

/* ===============================
   ACTIVE MENU
================================ */
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidebar">
  <h2 class="logo">SipWarga!</h2>

  <div class="menu">
    <h3>Warga</h3>
    <ul>
      <li <?php echo ($current_page == 'dashboard_warga.php') ? 'class="active"' : ''; ?>>
        <i class="fa-solid fa-house"></i>
        <a href="dashboard_warga.php"> Dashboard</a>
      </li>

      <li <?php echo ($current_page == 'data_pribadi.php') ? 'class="active"' : ''; ?>>
        <i class="fa-solid fa-user"></i>
        <a href="data_pribadi.php"> Data Pribadi</a>
      </li>

      <li <?php echo ($current_page == 'data_keluarga.php') ? 'class="active"' : ''; ?>>
        <i class="fa-solid fa-users"></i>
        <a href="data_keluarga.php"> Data Keluarga</a>
      </li>

      <li <?php echo ($current_page == 'riwayat_pengajuan.php') ? 'class="active"' : ''; ?>>
        <i class="fa-solid fa-clock-rotate-left"></i>
        <a href="riwayat_pengajuan.php"> Riwayat Pengajuan</a>
      </li>

      <li <?php echo ($current_page == 'pengaturan_warga.php') ? 'class="active"' : ''; ?>>
        <i class="fa-solid fa-gear"></i>
        <a href="pengaturan_warga.php"> Pengaturan</a>
      </li>

      <li <?php echo ($current_page == 'faq-warga.php') ? 'class="active"' : ''; ?>>
        <i class="fa-solid fa-question-circle"></i>
        <a href="faq-warga.php"> FAQ</a>
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
