<?php
/**
 * Database Configuration
 * CTF Lab - Educational Purpose Only
 */

// Start session for authentication
session_start();

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');  // Update with your MySQL password
define('DB_NAME', 'ctf_lab');

// Create database connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
