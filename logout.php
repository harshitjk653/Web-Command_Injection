<?php
/**
 * Logout Handler
 * CTF Lab - Educational Purpose Only
 */

session_start();
session_destroy();
header('Location: index.php');
exit();
?>
