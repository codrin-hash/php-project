CREATE DATABASE IF NOT EXISTS products_db;
USE products_db;

CREATE TABLE IF NOT EXISTS products(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    description text,
    price DECIMAL(5,2) NOT NULL,
    image VARCHAR(100) NOT NULL,
    availability_date DATE,
    stock_status BOOLEAN DEFAULT 1
);
