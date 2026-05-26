<?php
require_once __DIR__ . '/../../config/db.php';

$id    = (int) $_GET['id'];
$query = "DELETE FROM member WHERE id = $id";

if (mysqli_query($koneksi, $query)) {
    header("Location: index.php?sukses=hapus");
} else {
    header("Location: index.php?error=hapus");
}
exit;
?>