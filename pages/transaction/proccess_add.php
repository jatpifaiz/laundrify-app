<?php
require_once __DIR__ . '/../../config/db.php';

$id_member  = (int) ($_POST['id_member']  ?? 0);
$id_layanan = (int) ($_POST['id_layanan'] ?? 0);
$berat_kg   = (int) ($_POST['berat_kg']   ?? 0);
$total_harga = (float) ($_POST['total_harga'] ?? 0);
$tgl_masuk  = trim($_POST['tgl_masuk']  ?? '');
$tgl_selesai = trim($_POST['tgl_selesai'] ?? '');
$status     = trim($_POST['status'] ?? 'antri');

$allowed_status = ['antri', 'proses', 'selesai', 'diambil'];
if (!in_array($status, $allowed_status)) $status = 'antri';

if ($id_member <= 0 || $id_layanan <= 0 || $berat_kg <= 0 || empty($tgl_masuk)) {
    header("Location: add.php?error=1");
    exit;
}

$tgl_selesai_val = !empty($tgl_selesai) ? "'$tgl_selesai'" : "NULL";
$query = "INSERT INTO transaksi (id_member, id_layanan, berat_kg, total_harga, status, tgl_masuk, tgl_selesai)
          VALUES ($id_member, $id_layanan, $berat_kg, $total_harga, '$status', '$tgl_masuk', $tgl_selesai_val)";

if (mysqli_query($koneksi, $query)) {
    header("Location: index.php?sukses=tambah");
} else {
    header("Location: add.php?error=query");
}
exit;
?>
