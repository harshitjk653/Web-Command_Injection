<?php
/**
 * Fake 404 Page - Hidden Admin Access
 * CTF Lab - Educational Purpose Only
 * 
 * User must click reload 10 times to access real admin dashboard
 */

session_start();

// Must be logged in as admin
if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'A-kira' || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../index.php');
    exit();
}

// Handle reload click
if (isset($_POST['reload'])) {
    $_SESSION['reload_count'] = ($_SESSION['reload_count'] ?? 0) + 1;
    
    // After 10 clicks, redirect to real dashboard
    if ($_SESSION['reload_count'] >= 10) {
        header('Location: dashboard.php');
        exit();
    }
}

$count = $_SESSION['reload_count'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #0a0a0f;
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .error-container {
            text-align: center;
            padding: 2rem;
        }
        
        .error-code {
            font-size: 8rem;
            font-weight: 700;
            color: #ff4757;
            text-shadow: 0 0 40px rgba(255, 71, 87, 0.3);
            line-height: 1;
            margin-bottom: 1rem;
        }
        
        .error-message {
            font-size: 1.5rem;
            color: #8b8b8b;
            margin-bottom: 2rem;
        }
        
        .error-desc {
            color: #555;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }
        
        form {
            display: inline;
        }
        
        .reload-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            border: none;
            padding: 0.875rem 2rem;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .reload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
        }
        
        .home-link {
            display: block;
            margin-top: 1.5rem;
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .home-link:hover {
            text-decoration: underline;
        }
        
        /* Hidden counter - for debugging only */
        .debug-counter {
            position: fixed;
            bottom: 10px;
            right: 10px;
            font-size: 0.7rem;
            color: #1a1a1a;
            /* Almost invisible */
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-message">Page Not Found</div>
        <p class="error-desc">The page you're looking for doesn't exist or has been moved.</p>
        
        <form method="POST">
            <button type="submit" name="reload" class="reload-btn">Reload Page</button>
        </form>
        
        <a href="../index.php" class="home-link">← Back to Home</a>
    </div>
    
    <!-- Hidden counter for CTF hint hunters -->
    <div class="debug-counter"><?php echo $count; ?>/10</div>
</body>
</html>
