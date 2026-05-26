<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/session.php';
requireUser();

$uid    = (int) $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($action === 'update_info') {
    $nama   = trim(mysqli_real_escape_string($koneksi, $_POST['nama']   ?? ''));
    $no_hp  = trim(mysqli_real_escape_string($koneksi, $_POST['no_hp']  ?? ''));
    $alamat = trim(mysqli_real_escape_string($koneksi, $_POST['alamat'] ?? ''));

    if (empty($nama) || empty($no_hp) || empty($alamat)) {
        header("Location: profile.php?error=empty");
        exit;
    }

    mysqli_query($koneksi, "UPDATE users SET nama='$nama', no_hp='$no_hp', alamat='$alamat' WHERE id=$uid");

    $id_member = (int) ($_SESSION['id_member'] ?? 0);
    if ($id_member > 0) {
        mysqli_query($koneksi, "UPDATE member SET nama='$nama', no_hp='$no_hp', alamat='$alamat' WHERE id=$id_member");
    }

    $_SESSION['nama'] = $nama;
    header("Location: profile.php?sukses=1");
    exit;
}

if ($action === 'change_password') {
    $password_lama = $_POST['password_lama'] ?? '';
    $password_baru = $_POST['password_baru'] ?? '';
    $konfirm_baru  = $_POST['konfirm_baru']  ?? '';

    if (empty($password_lama) || empty($password_baru) || empty($konfirm_baru)) {
        header("Location: profile.php?error=empty");
        exit;
    }
    if ($password_baru !== $konfirm_baru) {
        header("Location: profile.php?error=mismatch");
        exit;
    }
    if (strlen($password_baru) < 6) {
        header("Location: profile.php?error=short_pw");
        exit;
    }

    $user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT password FROM users WHERE id=$uid LIMIT 1"));
    if (!password_verify($password_lama, $user['password'])) {
        header("Location: profile.php?error=wrong_pw");
        exit;
    }

    $hashed = mysqli_real_escape_string($koneksi, password_hash($password_baru, PASSWORD_DEFAULT));
    mysqli_query($koneksi, "UPDATE users SET password='$hashed' WHERE id=$uid");
    header("Location: profile.php?sukses=1");
    exit;
}

if ($action === 'request_membership') {
    $cur = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT membership, membership_request FROM users WHERE id=$uid LIMIT 1"));
    if ($cur && $cur['membership'] === 'reguler' && $cur['membership_request'] === 'none') {
        mysqli_query($koneksi, "UPDATE users SET membership_request='pending' WHERE id=$uid");
    }
    header("Location: profile.php?sukses=1"); exit;
}

header("Location: profile.php");
exit;
