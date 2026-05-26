<?php
require_once __DIR__ . '/../../config/db.php';

// Ambil & bersihkan input
$nama   = trim(mysqli_real_escape_string($koneksi, $_POST['nama']));
$no_hp  = trim(mysqli_real_escape_string($koneksi, $_POST['no_hp']));
$alamat = trim(mysqli_real_escape_string($koneksi, $_POST['alamat']));

// Validasi tidak boleh kosong
if (empty($nama) || empty($no_hp) || empty($alamat)) {
    header("Location: add.php?error=1");
    exit;
}

// Query INSERT
$query = "INSERT INTO member (nama, no_hp, alamat) VALUES ('$nama', '$no_hp', '$alamat')";

if (mysqli_query($koneksi, $query)) {
    header("Location: index.php?sukses=tambah");
} else {
    header("Location: add.php?error=query");
}
exit;
?>