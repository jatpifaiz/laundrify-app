<?php
require_once 'config/db.php';
require_once 'config/session.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: " . $base_url . "landing.php");
} elseif ($_SESSION['role'] === 'admin') {
    header("Location: " . $base_url . "dashboard.php");
} else {
    header("Location: " . $base_url . "user/index.php");
}
exit;
