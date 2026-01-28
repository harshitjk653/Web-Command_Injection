<?php
/**
 * Login Handler
 */

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Check if user exists
    $check_query = "SELECT * FROM users WHERE username='$username'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (!$check_result || mysqli_num_rows($check_result) === 0) {
        header('Location: index.php?error=notfound');
        exit();
    }
    
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        if ($username === 'A-kira') {
            $_SESSION['user'] = 'A-kira';
            $_SESSION['is_admin'] = true;
            $_SESSION['reload_count'] = 0;
            header('Location: admin/fake404.php');
            exit();
        }
        
        $_SESSION['user'] = $user['username'] ?? $username;
        $_SESSION['is_admin'] = false;
        header('Location: dashboard.php');
        exit();
    } else {
        header('Location: index.php?error=1');
        exit();
    }
}

header('Location: index.php');
exit();
?>
