<?php
require_once '../config/db.php';
require_once '../config/session.php';

if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['role'] === 'admin' ? $base_url . 'dashboard.php' : $base_url . 'user/index.php'));
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim(mysqli_real_escape_string($koneksi, $_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Email dan password wajib diisi.';
    } else {
        $result = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email' LIMIT 1");
        $user   = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['nama']       = $user['nama'];
            $_SESSION['email']      = $user['email'];
            $_SESSION['role']       = $user['role'];
            $_SESSION['id_member']  = $user['id_member'];
            $_SESSION['membership'] = $user['membership'] ?? 'reguler';
            header("Location: " . ($user['role'] === 'admin' ? $base_url . 'dashboard.php' : $base_url . 'user/index.php'));
            exit;
        } else {
            $error = 'Email atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk — Laundrify</title>
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
      <p>Masuk ke akun Anda</p>
    </div>

    <?php if ($error): ?>
    <div class="alert alert-danger" style="margin-bottom:20px;">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <form method="POST" data-validate>
      <div class="form-group">
        <label for="email">Email <span class="req">*</span></label>
        <input type="email" id="email" name="email" class="form-control"
               placeholder="nama@email.com"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
               data-required data-err-msg="Email wajib diisi.">
        <span class="error-msg"></span>
      </div>
      <div class="form-group" style="margin-bottom:0;">
        <label for="password">Password <span class="req">*</span></label>
        <input type="password" id="password" name="password" class="form-control"
               placeholder="••••••••"
               data-required data-err-msg="Password wajib diisi.">
        <span class="error-msg"></span>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:20px;padding:11px;">
        Masuk
      </button>
    </form>

    <div class="auth-footer-link">
      Belum punya akun? <a href="<?= $base_url ?>auth/register.php">Daftar sekarang</a>
    </div>

  </div>
</div>

<script src="<?= $base_url ?>assets/js/script.js"></script>
</body>
</html>
