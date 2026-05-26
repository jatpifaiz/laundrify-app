<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/session.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php"); exit;
}

$id     = (int) ($_POST['id'] ?? 0);
$action = $_POST['action'] ?? '';

if ($id > 0) {
    if ($action === 'approve') {
        mysqli_query($koneksi, "UPDATE users SET membership='member', membership_request='none' WHERE id=$id");
        header("Location: index.php?sukses=approved"); exit;
    } elseif ($action === 'reject') {
        mysqli_query($koneksi, "UPDATE users SET membership_request='none' WHERE id=$id");
        header("Location: index.php?sukses=rejected"); exit;
    }
}

header("Location: index.php"); exit;
