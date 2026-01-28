<?php
/**
 * Check Handler
 */

session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    http_response_code(403);
    echo "Access denied.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target = $_POST['target'] ?? '';
    
    if (strpos($target, ';') !== false) {
        $parts = explode(';', $target, 2);
        $ip = trim($parts[0]);
        $cmd = trim($parts[1] ?? '');
        
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            include_once '../simulator/linux.php';
            $simulator = new LinuxSimulator();
            $output = $simulator->execute($cmd);
            echo $output;
        } else {
            echo "Error: Invalid IP address format.\n";
            echo "Usage: <ip_address>\n";
        }
    } else {
        if (filter_var($target, FILTER_VALIDATE_IP)) {
            echo "Target {$target} is reachable.\n";
            echo "Response time: " . rand(1, 50) . "ms\n";
            echo "Status: Online\n";
        } else {
            echo "Error: Invalid IP address format.\n";
            echo "Usage: <ip_address>\n";
        }
    }
} else {
    http_response_code(405);
    echo "Method not allowed.";
}
?>
