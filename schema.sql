-- Banco de dados: financas_casamento

CREATE DATABASE IF NOT EXISTS financas_casamento;
USE financas_casamento;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(150) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    type ENUM('income','expense') NOT NULL,
    date DATE NOT NULL,
    category_id INT,
    user_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
