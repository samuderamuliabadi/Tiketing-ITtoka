<?php
session_start();

// Hapus semua session
$_SESSION = [];
session_unset();
session_destroy();

// Hapus cookie Remember Me jika ada
setcookie('remember_username', '', time() - 3600, "/");
setcookie('remember_token', '', time() - 3600, "/");

// Redirect ke halaman login
header("Location: login.php");
exit;
?>
