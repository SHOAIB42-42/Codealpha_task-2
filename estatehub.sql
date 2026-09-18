-- EstateHub: Smart Property & Rental Management System
-- Updated Database Schema for Phase 3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Create Database
DROP DATABASE IF EXISTS estatehub_db;
CREATE DATABASE IF NOT EXISTS estatehub_db;
USE estatehub_db;

-- 1. Users Table (Authentication)
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','tenant') NOT NULL DEFAULT 'tenant',
  `profile_image` varchar(255) DEFAULT 'default_user.png',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Property Categories
CREATE TABLE IF NOT EXISTS `property_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Properties Table
CREATE TABLE IF NOT EXISTS `properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `type` enum('Rent','Sale') NOT NULL,
  `category_id` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `bedrooms` int(11) NOT NULL,
  `location_area` varchar(255) NOT NULL,
  `city` varchar(100) DEFAULT 'Lahore',
  `status` enum('Available','Occupied','Sold') NOT NULL DEFAULT 'Available',
  `image` varchar(255) DEFAULT 'default_property.jpg',
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `properties_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `property_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Property Images (Gallery Slider)
CREATE TABLE IF NOT EXISTS `property_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_main` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  CONSTRAINT `property_images_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Agents Table
CREATE TABLE IF NOT EXISTS `agents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `cell_no` varchar(20) NOT NULL,
  `operational_area` varchar(255) NOT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `profile_image` varchar(255) DEFAULT 'default_agent.png',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Tenants Table
CREATE TABLE IF NOT EXISTS `tenants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(100) NOT NULL,
  `contact_email` varchar(100) NOT NULL,
  `profile_image` varchar(255) DEFAULT 'default_tenant.png',
  `property_id` int(11) DEFAULT NULL,
  `lease_start` date DEFAULT NULL,
  `lease_end` date DEFAULT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `registered_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `property_id` (`property_id`),
  CONSTRAINT `tenants_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tenants_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Payments Table (Advanced)
CREATE TABLE IF NOT EXISTS `payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `amount_paid` decimal(15,2) NOT NULL,
  `billing_month` varchar(50) NOT NULL,
  `payment_method` enum('Cash','Bank Transfer','Credit Card','Online') NOT NULL DEFAULT 'Cash',
  `payment_status` enum('Paid','Pending','Overdue') NOT NULL DEFAULT 'Pending',
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`payment_id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `tenant_id` (`tenant_id`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Activity Logs Table
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. Notifications Table
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 11. Visit Bookings Table
CREATE TABLE IF NOT EXISTS `visit_bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `visitor_name` varchar(100) NOT NULL,
  `visitor_email` varchar(100) NOT NULL,
  `visitor_phone` varchar(20) NOT NULL,
  `visit_date` date NOT NULL,
  `visit_time` varchar(50) NOT NULL,
  `notes` text DEFAULT NULL,
  `booking_status` enum('Pending','Approved','Rejected','Completed') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `visit_bookings_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE,
  CONSTRAINT `visit_bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. Saved Searches / Favorites Table
CREATE TABLE IF NOT EXISTS `property_favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `property_id` (`property_id`),
  CONSTRAINT `favorites_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `favorites_prop` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample Data

-- Users (Password is 'password' hashed)
INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
(2, 'tenant1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'tenant');

-- Property Categories
INSERT INTO `property_categories` (`id`, `name`, `description`) VALUES 
(1, 'Residential', 'Houses, Apartments, Villas for residential living.'),
(2, 'Commercial', 'Offices, Shops, Plazas for business purposes.'),
(3, 'Industrial', 'Factories, Warehouses.'),
(4, 'Land', 'Plots, Agricultural Land.');

-- Properties
INSERT INTO `properties` (`id`, `title`, `type`, `category_id`, `price`, `bedrooms`, `location_area`, `city`, `status`, `description`) VALUES   
(1, 'Luxury Apartment', 'Rent', 1, 55000.00, 3, 'DHA Phase 6', 'Lahore', 'Available', 'A high-end luxury apartment with modern amenities.'),
(2, 'Modern Villa', 'Sale', 1, 85000000.00, 5, 'Gulberg', 'Lahore', 'Available', 'Spacious villa with a garden and private pool.'),
(3, 'Cozy Studio', 'Rent', 1, 25000.00, 1, 'Model Town', 'Lahore', 'Occupied', 'Perfect for students or young professionals.'), 
(4, 'Commercial Office', 'Rent', 2, 120000.00, 0, 'Johar Town', 'Lahore', 'Available', 'Spacious office space in a prime business district.');

-- Agents
INSERT INTO `agents` (`id`, `full_name`, `cell_no`, `operational_area`, `rating`) VALUES
(1, 'John Doe', '0300-1234567', 'DHA', 4.5),
(2, 'Jane Smith', '0321-7654321', 'Gulberg', 4.8),
(3, 'Ali Khan', '0345-1122334', 'Johar Town', 4.2);

-- Tenants
INSERT INTO `tenants` (`id`, `user_id`, `user_name`, `contact_email`, `property_id`, `lease_start`, `lease_end`) VALUES
(1, 2, 'tenant1', 'tenant1@example.com', 3, '2026-01-01', '2026-12-31');

-- Payments
INSERT INTO `payments` (`payment_id`, `tenant_id`, `invoice_number`, `amount_paid`, `billing_month`, `payment_method`, `payment_status`) VALUES
(1, 1, 'INV-1001', 55000.00, 'May 2026', 'Bank Transfer', 'Paid'),
(2, 1, 'INV-1002', 55000.00, 'June 2026', 'Cash', 'Pending');

-- Activity Logs
INSERT INTO `activity_logs` (`id`, `user_id`, `action`) VALUES
(1, 1, 'Admin logged in'),
(2, 1, 'Added new property: Luxury Apartment');

COMMIT;
