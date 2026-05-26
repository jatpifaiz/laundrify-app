<?php
require_once __DIR__ . '/../../config/db.php';

$id = (int) ($_GET['id'] ?? 0);

if (mysqli_query($koneksi, "DELETE FROM transaksi WHERE id = $id")) {
    header("Location: index.php?sukses=hapus");
} else {
    header("Location: index.php?error=hapus");
}
exit;
?>
