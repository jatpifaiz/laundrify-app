<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function requireAdmin() {
    global $base_url;
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: " . $base_url . "auth/login.php");
        exit;
    }
}

function requireUser() {
    global $base_url;
    if (!isset($_SESSION['user_id'])) {
        header("Location: " . $base_url . "auth/login.php");
        exit;
    }
    if ($_SESSION['role'] === 'admin') {
        header("Location: " . $base_url . "dashboard.php");
        exit;
    }
}
