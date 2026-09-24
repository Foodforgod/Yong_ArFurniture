CREATE DATABASE IF NOT EXISTS `ar_furniture` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ar_furniture`;

CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default admin: admin / password123
INSERT INTO `admins` (`username`, `password_hash`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `name` VARCHAR(100) NOT NULL,
  `sort_order` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO `categories` (`slug`, `name`, `sort_order`, `is_active`) VALUES
('sofas', 'Sofas', 1, 1),
('chairs', 'Chairs', 2, 1),
('tables', 'Tables', 3, 1),
('beds', 'Beds', 4, 1),
('storage', 'Storage', 5, 1),
('lighting', 'Lighting', 6, 1);

CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NOT NULL,
  `slug` VARCHAR(150) NOT NULL UNIQUE,
  `name` VARCHAR(150) NOT NULL,
  `description` TEXT,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `currency` VARCHAR(10) NOT NULL DEFAULT 'RM',
  `glb_path` VARCHAR(255) NOT NULL,
  `usdz_path` VARCHAR(255) DEFAULT NULL,
  `thumb_path` VARCHAR(255) DEFAULT NULL,
  `width_cm` DECIMAL(6,2) DEFAULT 0.00,
  `height_cm` DECIMAL(6,2) DEFAULT 0.00,
  `depth_cm` DECIMAL(6,2) DEFAULT 0.00,
  `is_active` TINYINT(1) DEFAULT 1,
  `sort_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO `products` (`category_id`, `slug`, `name`, `description`, `price`, `currency`, `glb_path`, `thumb_path`, `width_cm`, `height_cm`, `depth_cm`, `is_active`, `sort_order`) VALUES
(1, 'nordic-lounge-sofa', 'Nordic Lounge Sofa', 'A comfortable 3-seater fabric sofa with solid wooden legs designed for modern living rooms.', 1299.00, 'RM', 'models/example.glb', 'uploads/thumbs/sofa.jpg', 210.00, 85.00, 90.00, 1, 1),
(2, 'ergonomic-office-chair', 'Ergonomic Office Chair', 'Breathable mesh office chair with adjustable lumbar support and armrests.', 450.00, 'RM', 'models/example.glb', 'uploads/thumbs/chair.jpg', 65.00, 110.00, 65.00, 1, 2),
(3, 'minimalist-coffee-table', 'Minimalist Coffee Table', 'Oak veneer coffee table with clean lines and sturdy lower shelf storage.', 320.00, 'RM', 'models/example.glb', 'uploads/thumbs/table.jpg', 110.00, 45.00, 60.00, 1, 3);