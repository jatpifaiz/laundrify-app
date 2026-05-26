<?php
require_once __DIR__ . '/../../config/db.php';

$id           = (int) ($_POST['id'] ?? 0);
$nama_layanan = trim(mysqli_real_escape_string($koneksi, $_POST['nama_layanan'] ?? ''));
$harga_per_kg = trim($_POST['harga_per_kg'] ?? '');

if (empty($nama_layanan) || !is_numeric($harga_per_kg) || (float)$harga_per_kg <= 0) {
    header("Location: edit.php?id=$id&error=1");
    exit;
}

$harga_per_kg = (float) $harga_per_kg;
$query = "UPDATE layanan SET nama_layanan='$nama_layanan', harga_per_kg=$harga_per_kg WHERE id=$id";

if (mysqli_query($koneksi, $query)) {
    header("Location: index.php?sukses=edit");
} else {
    header("Location: edit.php?id=$id&error=query");
}
exit;
?>
