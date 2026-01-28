<?php
/**
 * Linux Command Simulator
 */

class LinuxSimulator {
    // Simulated filesystem
    private $filesystem = [
        '/' => ['home', 'var', 'tmp', 'etc', 'data'],
        '/home' => ['admin', 'user'],
        '/home/admin' => ['.bash_history', 'notes.txt'],
        '/home/user' => ['readme.txt'],
        '/var' => ['log', 'www'],
        '/var/log' => ['auth.log', 'syslog'],
        '/var/www' => ['html'],
        '/tmp' => [],
        '/etc' => ['passwd', 'hosts', 'shadow'],
        '/data' => ['flag.txt', 'config.ini', 'secret'],
        '/data/secret' => ['part1.txt', 'part2.txt', 'part3.txt'],
    ];
    
    // File contents
    private $files = [
        '/home/admin/notes.txt' => "Remember to check the /data directory for sensitive files.\n",
        '/home/admin/.bash_history' => "cd /data\nls -la\ncat flag.txt\n",
        '/home/user/readme.txt' => "Welcome to the system.\n",
        '/var/log/auth.log' => "Jan 27 10:15:22 server sshd: Accepted password for admin\n",
        '/var/log/syslog' => "System running normally.\n",
        '/etc/passwd' => "root:x:0:0:root:/root:/bin/bash\nadmin:x:1000:1000:Admin:/home/admin:/bin/bash\n",
        '/etc/hosts' => "127.0.0.1 localhost\n192.168.1.1 gateway\n",
        '/etc/shadow' => "Permission denied\n",
        '/data/config.ini' => "[database]\nhost=localhost\nuser=admin\n",
        '/data/flag.txt' => "AKIRA{fake_flag}\n",
        '/data/secret/part1.txt' => "AKIRA{injection_",
        '/data/secret/part2.txt' => "is_done_",
        '/data/secret/part3.txt' => "successfully}\n",
    ];
    
    private $currentDir = '/';
    private $user = 'admin';
    
    /**
     * Execute a command string (may contain multiple commands via ;)
     */
    public function execute($input) {
        // INTENTIONALLY VULNERABLE: Split on semicolon to allow command chaining
        $commands = explode(';', $input);
        $output = '';
        
        foreach ($commands as $cmd) {
            $cmd = trim($cmd);
            if (empty($cmd)) continue;
            
            $result = $this->runCommand($cmd);
            if (!empty($result)) {
                $output .= $result;
            }
        }
        
        return $output ?: "Command completed.\n";
    }
    
    /**
     * Parse and run a single command
     */
    private function runCommand($cmdLine) {
        $parts = preg_split('/\s+/', $cmdLine, 2);
        $cmd = strtolower($parts[0]);
        $args = isset($parts[1]) ? trim($parts[1]) : '';
        
        switch ($cmd) {
            case 'whoami':
                return $this->user . "\n";
                
            case 'pwd':
                return $this->currentDir . "\n";
                
            case 'id':
                return "uid=1000({$this->user}) gid=1000({$this->user}) groups=1000({$this->user}),27(sudo)\n";
                
            case 'uname':
                return "Linux ctflab 5.15.0-generic #1 SMP x86_64 GNU/Linux\n";
                
            case 'hostname':
                return "ctflab\n";
                
            case 'ls':
                return $this->cmdLs($args);
                
            case 'cat':
                return $this->cmdCat($args);
                
            case 'cd':
                return $this->cmdCd($args);
                
            case 'find':
                return $this->cmdFind($args);
                
            case 'head':
                return $this->cmdHead($args);
                
            case 'tail':
                return $this->cmdTail($args);
                
            case 'echo':
                return $args . "\n";
                
            case 'date':
                return date("D M j H:i:s T Y") . "\n";
                
            case 'help':
                return "Available: whoami, pwd, ls, cat, cd, find, head, tail, echo, date, id, uname, hostname\n";
                
            default:
                return "bash: {$cmd}: command not found\n";
        }
    }
    
    /**
     * ls command
     */
    private function cmdLs($args) {
        $path = $this->resolvePath($args ?: $this->currentDir);
        
        if (!isset($this->filesystem[$path])) {
            return "ls: cannot access '{$args}': No such file or directory\n";
        }
        
        $items = $this->filesystem[$path];
        if (empty($items)) {
            return "";
        }
        
        // Check for -la flag
        if (strpos($args, '-l') !== false || strpos($args, '-a') !== false) {
            $output = "total " . count($items) . "\n";
            foreach ($items as $item) {
                $fullPath = rtrim($path, '/') . '/' . $item;
                $isDir = isset($this->filesystem[$fullPath]);
                $perms = $isDir ? 'drwxr-xr-x' : '-rw-r--r--';
                $size = isset($this->files[$fullPath]) ? strlen($this->files[$fullPath]) : 4096;
                $output .= "{$perms}  1 {$this->user} {$this->user}  {$size} Jan 27 10:00 {$item}\n";
            }
            return $output;
        }
        
        return implode("  ", $items) . "\n";
    }
    
    /**
     * cat command
     */
    private function cmdCat($args) {
        if (empty($args)) {
            return "cat: missing operand\n";
        }
        
        $path = $this->resolvePath($args);
        
        if (isset($this->files[$path])) {
            return $this->files[$path];
        }
        
        if (isset($this->filesystem[$path])) {
            return "cat: {$args}: Is a directory\n";
        }
        
        return "cat: {$args}: No such file or directory\n";
    }
    
    /**
     * cd command
     */
    private function cmdCd($args) {
        if (empty($args) || $args === '~') {
            $this->currentDir = '/home/' . $this->user;
            return "";
        }
        
        $path = $this->resolvePath($args);
        
        if (isset($this->filesystem[$path])) {
            $this->currentDir = $path;
            return "";
        }
        
        return "bash: cd: {$args}: No such file or directory\n";
    }
    
    /**
     * find command
     */
    private function cmdFind($args) {
        $output = "";
        
        // Find all files/dirs containing the search term
        $search = str_replace(['-name', '-type f', '-type d', '*', "'", '"'], '', $args);
        $search = trim($search);
        
        // If no args, list current dir
        if (empty($search) || $search === '.') {
            $search = '';
        }
        
        foreach ($this->filesystem as $dir => $items) {
            if (!empty($search) && strpos($dir, $search) === false) {
                // Check items in this directory
                $matchedItems = [];
                foreach ($items as $item) {
                    if (strpos($item, $search) !== false) {
                        $matchedItems[] = rtrim($dir, '/') . '/' . $item;
                    }
                }
                if (!empty($matchedItems)) {
                    $output .= implode("\n", $matchedItems) . "\n";
                }
            } else if (empty($search)) {
                $output .= $dir . "\n";
                foreach ($items as $item) {
                    $output .= rtrim($dir, '/') . '/' . $item . "\n";
                }
            } else {
                $output .= $dir . "\n";
            }
        }
        
        return $output ?: "find: no matches\n";
    }
    
    /**
     * head command
     */
    private function cmdHead($args) {
        $path = $this->resolvePath(preg_replace('/-n\s*\d+\s*/', '', $args));
        
        if (isset($this->files[$path])) {
            $lines = explode("\n", $this->files[$path]);
            return implode("\n", array_slice($lines, 0, 10));
        }
        
        return "head: {$args}: No such file or directory\n";
    }
    
    /**
     * tail command
     */
    private function cmdTail($args) {
        $path = $this->resolvePath(preg_replace('/-n\s*\d+\s*/', '', $args));
        
        if (isset($this->files[$path])) {
            $lines = explode("\n", $this->files[$path]);
            return implode("\n", array_slice($lines, -10));
        }
        
        return "tail: {$args}: No such file or directory\n";
    }
    
    /**
     * Resolve relative/absolute paths
     */
    private function resolvePath($path) {
        $path = trim($path);
        
        // Handle empty or current dir
        if (empty($path) || $path === '.') {
            return $this->currentDir;
        }
        
        // Handle home directory
        if ($path === '~' || strpos($path, '~/') === 0) {
            $path = str_replace('~', '/home/' . $this->user, $path);
        }
        
        // Handle absolute path
        if ($path[0] !== '/') {
            $path = rtrim($this->currentDir, '/') . '/' . $path;
        }
        
        // Normalize path (handle .. and .)
        $parts = explode('/', $path);
        $resolved = [];
        
        foreach ($parts as $part) {
            if ($part === '..') {
                array_pop($resolved);
            } else if ($part !== '' && $part !== '.') {
                $resolved[] = $part;
            }
        }
        
        return '/' . implode('/', $resolved);
    }
}

// Handle incoming request
if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['cmd'])) {
    $input = $_POST['input'] ?? $_GET['cmd'] ?? '';
    
    $simulator = new LinuxSimulator();
    $output = $simulator->execute($input);
    
    echo $output;
}
?>
