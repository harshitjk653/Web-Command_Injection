<?php
/**
 * Registration Handler
 * CTF Lab - Educational Purpose Only
 * 
 * Stores passwords in plaintext (intentional for CTF lab)
 */

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validate passwords match
    if ($password !== $confirm_password) {
        header('Location: signup.php?error=mismatch');
        exit();
    }
    
    // Check if username already exists
    $check_query = "SELECT id FROM users WHERE username = '" . mysqli_real_escape_string($conn, $username) . "'";
    $check_result = mysqli_query($conn, $check_query);
    
    if ($check_result && mysqli_num_rows($check_result) > 0) {
        header('Location: signup.php?error=exists');
        exit();
    }
    
    // Insert new user (plaintext password - intentional for CTF)
    $insert_query = "INSERT INTO users (username, password) VALUES ('" . 
                    mysqli_real_escape_string($conn, $username) . "', '" . 
                    mysqli_real_escape_string($conn, $password) . "')";
    
    if (mysqli_query($conn, $insert_query)) {
        header('Location: index.php?registered=1');
        exit();
    } else {
        header('Location: signup.php?error=failed');
        exit();
    }
}

// Redirect to signup if accessed directly
header('Location: signup.php');
exit();
?>
