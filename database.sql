CREATE DATABASE IF NOT EXISTS cmovil_db;
USE cmovil_db;

CREATE TABLE IF NOT EXISTS device_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    device_id VARCHAR(255) NOT NULL,
    phone_number VARCHAR(50),
    model VARCHAR(100),
    brand VARCHAR(100),
    os_version VARCHAR(50),
    battery_level INT,
    is_charging BOOLEAN,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    altitude DECIMAL(10, 2),
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
