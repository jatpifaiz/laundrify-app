<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/membership.php';
requireUser();

$id_layanan  = (int) ($_POST['id_layanan'] ?? 0);
$berat_kg    = (int) ($_POST['berat_kg']   ?? 0);
$tgl_masuk   = trim($_POST['tgl_masuk']    ?? '');
$tgl_selesai = trim($_POST['tgl_selesai']  ?? '');

if ($id_layanan <= 0 || $berat_kg <= 0 || empty($tgl_masuk)) {
    header("Location: booking.php?error=1");
    exit;
}

// Hitung dari DB — tidak percaya nilai dari client
$layanan_row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT harga_per_kg FROM layanan WHERE id=$id_layanan LIMIT 1"));
if (!$layanan_row) {
    header("Location: booking.php?error=1");
    exit;
}

// Ambil membership terbaru dari DB (bukan session, agar up-to-date)
$uid  = (int) $_SESSION['user_id'];
$u    = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT membership, id_member FROM users WHERE id=$uid LIMIT 1"));
$membership = $u['membership'] ?? 'reguler';
$id_member  = (int) ($u['id_member'] ?? $_SESSION['id_member'] ?? 0);

$harga_per_kg = (float) $layanan_row['harga_per_kg'];
$hasil        = hitungHargaAkhir($harga_per_kg * $berat_kg, $membership, $berat_kg);

$harga_dasar   = $hasil['harga_dasar'];
$diskon_persen = $hasil['diskon_persen'];
$total_harga   = $hasil['total_harga'];

$tgl_selesai_val = !empty($tgl_selesai) ? "'$tgl_selesai'" : "NULL";

$query = "INSERT INTO transaksi (id_user, id_member, id_layanan, berat_kg, total_harga, harga_dasar, diskon_persen, status, tgl_masuk, tgl_selesai)
          VALUES ($uid, $id_member, $id_layanan, $berat_kg, $total_harga, $harga_dasar, $diskon_persen, 'antri', '$tgl_masuk', $tgl_selesai_val)";

if (mysqli_query($koneksi, $query)) {
    header("Location: riwayat.php?sukses=tambah");
} else {
    header("Location: booking.php?error=query");
}
exit;
