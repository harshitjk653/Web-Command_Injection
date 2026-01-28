<?php
session_start();
$error = isset($_GET['error']) ? $_GET['error'] : false;
$error_message = '';

if ($error === '1') {
    $error_message = 'Invalid username or password';
} elseif ($error === 'notfound') {
    $error_message = 'User not found. Please register first.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | CTF Lab</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="logo">🔐</div>
        <h1>Welcome Back</h1>
        <p class="subtitle">Sign in to continue</p>
        
        <?php if ($error_message): ?>
        <div class="error-message show"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <form action="login.php" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autocomplete="username">
            </div>
            
            <div class="input-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                    <button type="button" class="toggle-password" aria-label="Toggle password visibility">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>
            
            <button type="submit">Sign In</button>
        </form>
        
        <div class="switch-link">
            Don't have an account? <a href="signup.php">Create one</a>
        </div>
    </div>
    
    <script>
        // Toggle password visibility
        document.querySelector('.toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
        });
    </script>
</body>
</html>
