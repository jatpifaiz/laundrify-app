<?php
require_once '../config/db.php';
require_once '../config/session.php';
session_unset();
session_destroy();
header("Location: " . $base_url . "auth/login.php");
exit;
