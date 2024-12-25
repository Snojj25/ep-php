-- Drop and create database
DROP DATABASE IF EXISTS ecommerce;
CREATE DATABASE ecommerce DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ecommerce;

-- Users table
CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('administrator', 'seller', 'customer') NOT NULL,
    address VARCHAR(100),
    house_number VARCHAR(10),
    postal_code VARCHAR(10),
    city VARCHAR(50),
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products table
CREATE TABLE products (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Orders table
CREATE TABLE orders (
    id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed', 'storno') NOT NULL DEFAULT 'pending',
    total_amount DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    shipping_address VARCHAR(255),  
    postal_code VARCHAR(20),  
    city VARCHAR(100),  
    phone VARCHAR(20),
    notes TEXT,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Order items table
CREATE TABLE order_items (  
    id INT NOT NULL AUTO_INCREMENT,  
    order_id INT NOT NULL,  
    product_id INT NOT NULL,  
    quantity INT NOT NULL,  
    price DECIMAL(10,2) NOT NULL,  
    PRIMARY KEY (id),  
    FOREIGN KEY (order_id) REFERENCES orders(id),  
    FOREIGN KEY (product_id) REFERENCES products(id)  
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; 

-- Insert default administrator
INSERT INTO users (
    first_name,
    last_name,
    email,
    password,
    role
) VALUES (
    'Admin',
    'User',
    'admin@example.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: 'password'
    'administrator'
);

-- Insert sample seller
INSERT INTO users (
    first_name,
    last_name,
    email,
    password,
    role
) VALUES (
    'Seller',
    'User',
    'seller@example.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: 'password'
    'seller'
);

-- Insert sample customer
INSERT INTO users (
    first_name,
    last_name,
    email,
    password,
    role,
    address,
    house_number,
    postal_code,
    city
) VALUES (
    'Customer',
    'User',
    'customer@example.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: 'password'
    'customer',
    'Sample Street',
    '123',
    '1000',
    'Ljubljana'
);

-- Insert sample products
INSERT INTO products (name, description, price) VALUES
('Laptop', 'High-performance laptop with latest specifications', 999.99),
('Smartphone', 'Latest model with advanced features', 599.99),
('Headphones', 'Wireless noise-cancelling headphones', 199.99),
('Tablet', '10-inch tablet with retina display', 399.99),
('Smartwatch', 'Fitness tracking smartwatch', 299.99);

-- Create indexes for better performance
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_products_is_active ON products(is_active);
CREATE INDEX idx_orders_user_id ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);