<?php
require_once __DIR__ . '/../../config/db.php';

$nama_layanan = trim(mysqli_real_escape_string($koneksi, $_POST['nama_layanan'] ?? ''));
$harga_per_kg = trim($_POST['harga_per_kg'] ?? '');

if (empty($nama_layanan) || !is_numeric($harga_per_kg) || (float)$harga_per_kg <= 0) {
    header("Location: add.php?error=1");
    exit;
}

$harga_per_kg = (float) $harga_per_kg;
$query = "INSERT INTO layanan (nama_layanan, harga_per_kg) VALUES ('$nama_layanan', $harga_per_kg)";

if (mysqli_query($koneksi, $query)) {
    header("Location: index.php?sukses=tambah");
} else {
    header("Location: add.php?error=query");
}
exit;
?>
