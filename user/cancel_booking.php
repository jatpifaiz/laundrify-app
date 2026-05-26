<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/session.php';
requireUser();

$id  = (int) ($_GET['id'] ?? 0);
$uid = (int) $_SESSION['user_id'];

$row = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT id, status FROM transaksi WHERE id=$id AND id_user=$uid LIMIT 1"));

if (!$row || $row['status'] !== 'antri') {
    header("Location: riwayat.php?error=1");
    exit;
}

mysqli_query($koneksi, "DELETE FROM transaksi WHERE id=$id AND id_user=$uid");
header("Location: riwayat.php?sukses=hapus");
exit;
