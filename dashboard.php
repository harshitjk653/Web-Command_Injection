<?php
/**
 * User Dashboard
 * CTF Lab - Educational Purpose Only
 */

require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

// Redirect admins to admin dashboard
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    header('Location: admin/dashboard.php');
    exit();
}

$username = htmlspecialchars($_SESSION['user']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | CTF Lab</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="dashboard-header">
            <h1>Dashboard</h1>
            <div class="user-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <?php echo $username; ?>
            </div>
        </div>
        
        <div class="dashboard-content">
            <p>Welcome, User. You are logged in as a standard user.</p>
        </div>
        
        <a href="logout.php" class="btn-secondary" style="display: block; text-align: center; text-decoration: none;">Sign Out</a>
    </div>
</body>
</html>
