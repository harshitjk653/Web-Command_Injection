<?php
session_start();
$error = $_GET['error'] ?? '';
$error_message = '';

switch ($error) {
    case 'mismatch':
        $error_message = 'Passwords do not match';
        break;
    case 'exists':
        $error_message = 'Username already exists';
        break;
    case 'failed':
        $error_message = 'Registration failed. Please try again.';
        break;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | CTF Lab</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="logo">🔐</div>
        <h1>Create Account</h1>
        <p class="subtitle">Join the platform</p>
        
        <?php if ($error_message): ?>
        <div class="error-message show"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <form action="register.php" method="POST" id="signupForm">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autocomplete="username">
            </div>
            
            <div class="input-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required autocomplete="new-password">
                    <button type="button" class="toggle-password" aria-label="Toggle password visibility">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="input-group">
                <label for="confirm_password">Confirm Password</label>
                <div class="password-wrapper">
                    <input type="password" id="confirm_password" name="confirm_password" required autocomplete="new-password">
                    <button type="button" class="toggle-password-confirm" aria-label="Toggle password visibility">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="error-message" id="clientError"></div>
            
            <button type="submit">Create Account</button>
        </form>
        
        <div class="switch-link">
            Already have an account? <a href="index.php">Sign in</a>
        </div>
    </div>
    
    <script>
        // Toggle password visibility
        document.querySelector('.toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
        });
        
        document.querySelector('.toggle-password-confirm').addEventListener('click', function() {
            const confirmInput = document.getElementById('confirm_password');
            confirmInput.type = confirmInput.type === 'password' ? 'text' : 'password';
        });
        
        // Client-side password validation
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const errorDiv = document.getElementById('clientError');
            
            if (password !== confirmPassword) {
                e.preventDefault();
                errorDiv.textContent = 'Passwords do not match';
                errorDiv.classList.add('show');
            }
        });
    </script>
</body>
</html>
