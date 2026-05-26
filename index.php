<?php
require_once 'config/session.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: /laundrify-app/landing.php");
} elseif ($_SESSION['role'] === 'admin') {
    header("Location: /laundrify-app/dashboard.php");
} else {
    header("Location: /laundrify-app/user/index.php");
}
exit;
