<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/session.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php"); exit;
}

$id         = (int) ($_POST['id'] ?? 0);
$membership = in_array($_POST['membership'] ?? '', ['reguler','member']) ? $_POST['membership'] : 'reguler';

if ($id > 0) {
    mysqli_query($koneksi, "UPDATE users SET membership='$membership' WHERE id=$id");
}

header("Location: index.php?sukses=upgrade"); exit;
