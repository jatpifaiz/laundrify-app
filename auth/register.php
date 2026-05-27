<?php
require_once '../config/db.php';
require_once '../config/session.php';

if (isset($_SESSION['user_id'])) {
    header("Location: " . $base_url . "user/index.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama    = trim(mysqli_real_escape_string($koneksi, $_POST['nama']    ?? ''));
    $email   = trim(mysqli_real_escape_string($koneksi, $_POST['email']   ?? ''));
    $no_hp   = trim(mysqli_real_escape_string($koneksi, $_POST['no_hp']   ?? ''));
    $alamat  = trim(mysqli_real_escape_string($koneksi, $_POST['alamat']  ?? ''));
    $password = $_POST['password']        ?? '';
    $konfirm  = $_POST['konfirm_password'] ?? '';

    if (empty($nama) || empty($email) || empty($password) || empty($no_hp) || empty($alamat)) {
        $error = 'Semua field wajib diisi.';
    } elseif ($password !== $konfirm) {
        $error = 'Konfirmasi password tidak cocok.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } else {
        $cek = mysqli_query($koneksi, "SELECT id FROM users WHERE email='$email' LIMIT 1");
        if (mysqli_num_rows($cek) > 0) {
            $error = 'Email sudah digunakan, silakan gunakan email lain.';
        } else {
            mysqli_query($koneksi, "INSERT INTO member (nama, no_hp, alamat) VALUES ('$nama','$no_hp','$alamat')");
            $id_member = (int) mysqli_insert_id($koneksi);

            $hashed = mysqli_real_escape_string($koneksi, password_hash($password, PASSWORD_DEFAULT));
            $q = "INSERT INTO users (nama, email, password, no_hp, alamat, id_member, role)
                  VALUES ('$nama','$email','$hashed','$no_hp','$alamat',$id_member,'user')";

            if (mysqli_query($koneksi, $q)) {
                $_SESSION['user_id']    = (int) mysqli_insert_id($koneksi);
                $_SESSION['nama']       = $nama;
                $_SESSION['email']      = $email;
                $_SESSION['role']       = 'user';
                $_SESSION['id_member']  = $id_member;
                $_SESSION['membership'] = 'reguler';
                header("Location: " . $base_url . "user/index.php?sukses=daftar");
                exit;
            } else {
                $error = 'Terjadi kesalahan. Coba lagi.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar — Laundrify</title>
  <link rel="icon" type="image/png" href="<?= $base_url ?>favicon.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="<?= $base_url ?>assets/css/style.css">
</head>
<body class="standalone">

<div class="auth-wrapper">
  <div class="auth-card">

    <div class="auth-logo">
      <div class="logo-mark">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
        </svg>
      </div>
      <h1>Laundrify</h1>
      <p>Buat akun pelanggan baru</p>
    </div>

    <?php if ($error): ?>
    <div class="alert alert-danger" style="margin-bottom:20px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" data-validate>

      <div class="form-group">
        <label for="nama">Nama Lengkap <span class="req">*</span></label>
        <input type="text" id="nama" name="nama" class="form-control"
               placeholder="Contoh: Budi Santoso"
               value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>"
               data-required data-err-msg="Nama wajib diisi.">
        <span class="error-msg"></span>
      </div>

      <div class="form-group">
        <label for="email">Email <span class="req">*</span></label>
        <input type="email" id="email" name="email" class="form-control"
               placeholder="nama@email.com"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
               data-required data-err-msg="Email wajib diisi.">
        <span class="error-msg"></span>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="password">Password <span class="req">*</span></label>
          <input type="password" id="password" name="password" class="form-control"
                 placeholder="Min. 6 karakter"
                 data-required data-err-msg="Password wajib diisi.">
          <span class="error-msg"></span>
        </div>
        <div class="form-group">
          <label for="konfirm_password">Konfirmasi <span class="req">*</span></label>
          <input type="password" id="konfirm_password" name="konfirm_password" class="form-control"
                 placeholder="Ulangi password"
                 data-required data-err-msg="Konfirmasi wajib diisi.">
          <span class="error-msg"></span>
        </div>
      </div>

      <div class="form-group">
        <label for="no_hp">No HP <span class="req">*</span></label>
        <input type="text" id="no_hp" name="no_hp" class="form-control"
               placeholder="08123456789"
               value="<?= htmlspecialchars($_POST['no_hp'] ?? '') ?>"
               data-required data-type="phone">
        <span class="error-msg"></span>
      </div>

      <div class="form-group" style="margin-bottom:0;">
        <label for="alamat">Alamat <span class="req">*</span></label>
        <textarea id="alamat" name="alamat" class="form-control"
                  placeholder="Jl. Merdeka No. 1, Bandung"
                  data-required data-err-msg="Alamat wajib diisi."><?= htmlspecialchars($_POST['alamat'] ?? '') ?></textarea>
        <span class="error-msg"></span>
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:20px;padding:11px;">
        Buat Akun
      </button>

    </form>

    <div class="auth-footer-link">
      Sudah punya akun? <a href="<?= $base_url ?>auth/login.php">Masuk di sini</a>
    </div>

  </div>
</div>

<script src="<?= $base_url ?>assets/js/script.js"></script>
</body>
</html>
