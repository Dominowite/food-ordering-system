-- สร้างฐานข้อมูล
CREATE DATABASE IF NOT EXISTS food_ordering_db;
USE food_ordering_db;

-- สร้างตาราง admins
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    approved BOOLEAN DEFAULT TRUE
);

-- สร้างตาราง employees
CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    job_title ENUM('staff', 'kitchen') NOT NULL,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    salary DECIMAL(10, 2)
);

-- สร้างตาราง roles
CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE
);

-- สร้างตาราง tables
CREATE TABLE IF NOT EXISTS tables (
    id INT AUTO_INCREMENT PRIMARY KEY,
    max_capacity INT NOT NULL,
    status ENUM('available', 'occupied') DEFAULT 'available'
);

-- สร้างตาราง menu_statuses
CREATE TABLE IF NOT EXISTS menu_statuses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status_name VARCHAR(50) NOT NULL UNIQUE
);

-- สร้างตาราง menus
CREATE TABLE IF NOT EXISTS menus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),
    status_id INT,
    FOREIGN KEY (status_id) REFERENCES menu_statuses(id)
);

-- สร้างตาราง orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    table_id INT,
    order_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (table_id) REFERENCES tables(id)
);

-- สร้างตาราง order_items
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    menu_id INT,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (menu_id) REFERENCES menus(id)
);

-- สร้างตาราง activity_logs
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    user_role ENUM('admin', 'employee') NOT NULL,
    action VARCHAR(255) NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES admins(id) -- หรือ employees(id) ตาม user_role
);
