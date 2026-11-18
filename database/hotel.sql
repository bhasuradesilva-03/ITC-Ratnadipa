-- database/hotel.sql — for the database stage (guide sections 8 and 10)
CREATE DATABASE IF NOT EXISTS itc_ratnadipa;
USE itc_ratnadipa;

CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    view_type VARCHAR(50),
    balcony ENUM('Yes','No') DEFAULT 'No',
    max_guests INT NOT NULL DEFAULT 2,
    image VARCHAR(255),
    status ENUM('Available','Unavailable') DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    package_name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    status ENUM('Active','Inactive') DEFAULT 'Active'
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    adults INT NOT NULL DEFAULT 1,
    children INT NOT NULL DEFAULT 0,
    guest_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    phone VARCHAR(30),
    total_price DECIMAL(10,2) NOT NULL,
    status ENUM('Pending','Confirmed','Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO rooms (room_name, description, price, view_type, balcony, max_guests, image) VALUES
('Towers', 'Luxury city and lake view room', 32000, 'City / Beira Lake View', 'No', 3, 'towers.jpg'),
('Towers Ocean View', 'Ocean-facing room with balcony', 38000, 'Ocean View', 'Yes', 3, 'towers-ocean.jpg'),
('ITC One', 'Premium ocean-view room', 52000, 'Ocean View', 'Yes', 4, 'itc-one.jpg'),
('Towers Suite', 'Large luxury suite with living area', 78000, 'Ocean View', 'Yes', 5, 'towers-suite.jpg');

INSERT INTO packages (package_name, description, price, image) VALUES
('Full Board', 'Breakfast + Lunch + Dinner', 18000, 'full-board.jpg'),
('Half Board', 'Breakfast + Dinner', 14000, 'half-board.jpg'),
('Day Out', 'Pool, Dining & Leisure Access', 8000, 'day-out.jpg'),
('Parties & Events', 'Weddings, Corporate & Special Events', 5000, 'events.jpg');
