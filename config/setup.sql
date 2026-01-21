-- Database Creation
CREATE DATABASE IF NOT EXISTS event_management_system;
USE event_management_system;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    full_name VARCHAR(100) DEFAULT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'manager', 'attendee') DEFAULT 'attendee',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Singers Table
CREATE TABLE IF NOT EXISTS singers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    genre VARCHAR(50),
    price DECIMAL(10, 2) DEFAULT 0.00
);

-- Activities Table
CREATE TABLE IF NOT EXISTS activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) DEFAULT 0.00
);

-- Events Table
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    event_date DATETIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) DEFAULT 0.00,
    total_tickets INT NOT NULL,
    available_tickets INT NOT NULL,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Event-Singers Junction Table (M:M)
CREATE TABLE IF NOT EXISTS event_singers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    singer_id INT NOT NULL,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (singer_id) REFERENCES singers(id) ON DELETE CASCADE
);

-- Event-Activities Junction Table (M:M)
CREATE TABLE IF NOT EXISTS event_activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    activity_id INT NOT NULL,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (activity_id) REFERENCES activities(id) ON DELETE CASCADE
);

-- Bookings Table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    total_price DECIMAL(10, 2) DEFAULT 0.00,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('confirmed', 'cancelled') DEFAULT 'confirmed',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

-- Booking-Activities Junction Table 
CREATE TABLE IF NOT EXISTS booking_activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    activity_id INT NOT NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (activity_id) REFERENCES activities(id) ON DELETE CASCADE
);

-- Site Settings Table
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    site_name VARCHAR(255) DEFAULT 'Event Management System',
    logo_path VARCHAR(255) DEFAULT NULL,
    primary_color VARCHAR(7) DEFAULT '#6366f1',
    secondary_color VARCHAR(7) DEFAULT '#a855f7'
);

-- Insert Default Site Settings
INSERT INTO site_settings (id, site_name, logo_path, primary_color, secondary_color)
VALUES (1, 'Event Management System', NULL, '#6366f1', '#a855f7')
ON DUPLICATE KEY UPDATE id=id;

-- Seed Sample Singers
INSERT INTO singers (name, genre, price) VALUES 
('Rahat Fateh Ali Khan', 'Qawwali', 5000.00),
('Atif Aslam', 'Pop', 7500.00),
('Arijit Singh', 'Bollywood', 8000.00),
('Sanam Marvi', 'Sindhi Folk', 3000.00),
('Ali Zafar', 'Pop/Rock', 6000.00)
ON DUPLICATE KEY UPDATE name=name;

-- Seed Sample Activities
INSERT INTO activities (name, description, price) VALUES 
('VIP Seating', 'Premium front-row seating with complimentary refreshments', 1500.00),
('Backstage Pass', 'Meet and greet with performers', 2500.00),
('Food Package', 'All-inclusive dinner buffet', 800.00),
('Photo Booth', 'Professional event photography session', 500.00),
('Parking Pass', 'Reserved parking spot', 300.00)
ON DUPLICATE KEY UPDATE name=name;
