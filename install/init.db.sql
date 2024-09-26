DROP DATABASE bd_appqr;
CREATE DATABASE bd_appqr;
USE bd_appqr;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('guest','employee', 'admin') DEFAULT 'guest',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE qrs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_qr VARCHAR(100) NOT NULL,
    color_qr VARCHAR(100) NOT NULL,
    description MEDIUMTEXT NOT NULL,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);
SELECT
    qrs.id AS qrs_id,
    qrs.name_qr AS qrs_name_qr,
    qrs.color_qr AS qrs_color_qr,
    qrs.description AS qrs_description,
    qrs.created_at AS qrs_created_at,
    users.id AS users_id,
    users.name AS users_name,
    users.email AS users_email,
    users.role AS users_role
    FROM qrs
    JOIN users ON qrs.created_by = users.id LIMIT 100;