# Web-Command_Injection

A CTF Lab environment designed for educational purposes, focusing on Command Injection vulnerabilities.

## 🚀 Quick Start

### Prerequisites
- **PHP** (7.4 or higher)
- **MySQL / MariaDB**

### Installation

1.  **Clone the Repository** (If you haven't already):
    ```bash
    git clone https://github.com/harshitjk653/Web-Command_Injection.git
    cd Web-Command_Injection
    ```

2.  **Database Setup**:
    - Start your MySQL server.
    - Create a database named `ctf_lab`:
      ```sql
      CREATE DATABASE ctf_lab;
      ```
    - Import the schema:
      ```bash
      mysql -u root -p ctf_lab < schema.sql
      ```

3.  **Configuration**:
    - Open `config.php` and update the `DB_PASS` with your MySQL root password:
      ```php
      define('DB_PASS', 'your_password_here');
      ```

4.  **Launch the Server**:
    - Run the built-in PHP development server:
      ```bash
      php -S localhost:8000
      ```

5.  **Access the Web Interface**:
    - Open your browser and go to `http://localhost:8000`.

### Default Credentials
- **Username**: `A-kira`
- **Password**: `idgafidcidk`

## ⚠️ Educational Purpose Only
This project is intentionally vulnerable and should only be run in a controlled local environment for learning and security research. Do not deploy this to a public server.