<?php
require_once '../includes/functions.php';
startSession();
$_SESSION = [];
session_destroy();
header('Location: ../auth/login.php?logged_out=1');
exit;
?>
