<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/session.php';
requireUser();

if (!isset($user_active)) $user_active = '';
if (!isset($page_title))  $page_title  = 'Laundrify';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title) ?> — Laundrify</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="/laundrify-app/assets/css/style.css">
</head>
<body class="standalone">

<div class="user-layout">

  <nav class="user-navbar">
    <a href="/laundrify-app/user/index.php" class="user-brand">
      <div class="logo-mark">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="white">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
        </svg>
      </div>
      <span>Laundrify</span>
    </a>

    <div class="user-nav-links" id="user-nav-links">
      <a href="/laundrify-app/user/index.php"
         class="user-nav-link <?= $user_active === 'home' ? 'aktif' : '' ?>">Beranda</a>
      <a href="/laundrify-app/user/booking.php"
         class="user-nav-link <?= $user_active === 'booking' ? 'aktif' : '' ?>">Booking</a>
      <a href="/laundrify-app/user/riwayat.php"
         class="user-nav-link <?= $user_active === 'riwayat' ? 'aktif' : '' ?>">Riwayat</a>
      <a href="/laundrify-app/user/profile.php"
         class="user-nav-link <?= $user_active === 'profil' ? 'aktif' : '' ?>"><?= htmlspecialchars($_SESSION['nama']) ?></a>
      <a href="/laundrify-app/auth/logout.php"
         class="user-nav-link user-nav-logout">Keluar</a>
    </div>

    <button class="user-nav-toggle" id="user-nav-toggle" aria-label="Buka menu" aria-expanded="false">
      <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
      </svg>
    </button>
  </nav>

  <div class="user-main">
