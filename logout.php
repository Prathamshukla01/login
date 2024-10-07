<?php
session_start();
$_SESSION = array();
session_destroy();

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Redirect to the login page
header("Location: login.php");
exit();

?>
