<?php
require_once __DIR__ . '/../../config/db.php';

$id          = (int) ($_POST['id']          ?? 0);
$id_member   = (int) ($_POST['id_member']   ?? 0);
$id_layanan  = (int) ($_POST['id_layanan']  ?? 0);
$berat_kg    = (int) ($_POST['berat_kg']    ?? 0);
$total_harga = (float) ($_POST['total_harga'] ?? 0);
$tgl_masuk   = trim($_POST['tgl_masuk']   ?? '');
$tgl_selesai = trim($_POST['tgl_selesai'] ?? '');
$status      = trim($_POST['status'] ?? 'antri');

$allowed_status = ['antri', 'proses', 'selesai', 'diambil'];
if (!in_array($status, $allowed_status)) $status = 'antri';

if ($id <= 0 || $id_member <= 0 || $id_layanan <= 0 || $berat_kg <= 0 || empty($tgl_masuk)) {
    header("Location: edit.php?id=$id&error=1");
    exit;
}

$tgl_selesai_val = !empty($tgl_selesai) ? "'$tgl_selesai'" : "NULL";
$query = "UPDATE transaksi
          SET id_member=$id_member, id_layanan=$id_layanan, berat_kg=$berat_kg,
              total_harga=$total_harga, status='$status',
              tgl_masuk='$tgl_masuk', tgl_selesai=$tgl_selesai_val
          WHERE id=$id";

if (mysqli_query($koneksi, $query)) {
    header("Location: index.php?sukses=edit");
} else {
    header("Location: edit.php?id=$id&error=query");
}
exit;
?>
