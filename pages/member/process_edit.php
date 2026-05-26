<?php
require_once __DIR__ . '/../../config/db.php';

$id     = (int) $_POST['id'];
$nama   = trim(mysqli_real_escape_string($koneksi, $_POST['nama']));
$no_hp  = trim(mysqli_real_escape_string($koneksi, $_POST['no_hp']));
$alamat = trim(mysqli_real_escape_string($koneksi, $_POST['alamat']));

if (empty($nama) || empty($no_hp) || empty($alamat)) {
    header("Location: edit.php?id=$id&error=1");
    exit;
}

$query = "UPDATE member SET nama='$nama', no_hp='$no_hp', alamat='$alamat' WHERE id=$id";

if (mysqli_query($koneksi, $query)) {
    header("Location: index.php?sukses=edit");
} else {
    header("Location: edit.php?id=$id&error=query");
}
exit;
?>