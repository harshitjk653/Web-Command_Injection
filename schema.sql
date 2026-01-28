-- Database Schema

CREATE DATABASE IF NOT EXISTS ctf_lab;
USE ctf_lab;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password, is_admin) VALUES ('A-kira', 'idgafidcidk', 1)
ON DUPLICATE KEY UPDATE password = 'idgafidcidk', is_admin = 1;
