CREATE DATABASE IF NOT EXISTS zinciri_kirma CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE zinciri_kirma;

CREATE TABLE IF NOT EXISTS habits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    target_days INT NOT NULL,
    created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS habit_chains (
    id INT AUTO_INCREMENT PRIMARY KEY,
    habit_id INT NOT NULL,
    date DATE NOT NULL,
    FOREIGN KEY (habit_id) REFERENCES habits(id) ON DELETE CASCADE,
    UNIQUE KEY unique_chain (habit_id, date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
