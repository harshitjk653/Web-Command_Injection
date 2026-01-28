<?php
/**
 * Admin Dashboard - Verification Tool
 * CTF Lab - Educational Purpose Only
 * 
 * UI appears as a normal network verification tool.
 * Hidden vulnerability exists in backend (check.php → linux.php)
 */

session_start();

// Check if user is logged in and has admin access
if (!isset($_SESSION['user']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../index.php');
    exit();
}

$username = htmlspecialchars($_SESSION['user']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | CTF Lab</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .admin-tools {
            margin-top: 1.5rem;
        }
        
        .tool-section {
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: var(--input-radius);
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .tool-section h3 {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }
        
        .tool-form {
            display: flex;
            gap: 0.75rem;
        }
        
        .tool-form input {
            flex: 1;
        }
        
        .tool-form button {
            padding: 0.875rem 1.5rem;
            white-space: nowrap;
        }
        
        .output-section {
            margin-top: 1rem;
            display: none;
        }
        
        .output-section.show {
            display: block;
        }
        
        .output-box {
            background: #0a0a0f;
            border: 1px solid var(--border-color);
            border-radius: var(--input-radius);
            padding: 1rem;
            font-family: 'JetBrains Mono', 'Fira Code', monospace;
            font-size: 0.85rem;
            color: #00ff88;
            white-space: pre-wrap;
            word-break: break-all;
            max-height: 300px;
            overflow-y: auto;
        }
        
        .loading {
            color: var(--accent-primary);
        }
        
        @media (max-width: 480px) {
            .tool-form {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container" style="max-width: 500px;">
        <div class="dashboard-header">
            <h1>Admin Dashboard</h1>
            <div class="user-badge admin-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
                Admin
            </div>
        </div>
        
        <div class="admin-tools">
            <div class="tool-section">
                <h3>Network Verification</h3>
                <form id="checkForm" class="tool-form">
                    <input type="text" id="target" name="target" required autocomplete="off">
                    <button type="submit">Check</button>
                </form>
                
                <div id="outputSection" class="output-section">
                    <pre id="output" class="output-box"></pre>
                </div>
            </div>
        </div>
        
        <a href="../logout.php" class="btn-secondary" style="display: block; text-align: center; text-decoration: none; margin-top: 1.5rem;">Sign Out</a>
    </div>
    
    <script>
        document.getElementById('checkForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const target = document.getElementById('target').value;
            const outputSection = document.getElementById('outputSection');
            const outputBox = document.getElementById('output');
            
            outputSection.classList.add('show');
            outputBox.textContent = 'Processing...';
            outputBox.classList.add('loading');
            
            try {
                const response = await fetch('check.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'target=' + encodeURIComponent(target)
                });
                
                const result = await response.text();
                outputBox.textContent = result;
                outputBox.classList.remove('loading');
            } catch (error) {
                outputBox.textContent = 'Error: Unable to reach target.';
                outputBox.classList.remove('loading');
            }
        });
    </script>
</body>
</html>
