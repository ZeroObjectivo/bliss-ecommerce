-- Safe demo seed for local onboarding and GitHub sharing.

SET NAMES utf8mb4;
CREATE DATABASE IF NOT EXISTS `bliss_ecommerce` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bliss_ecommerce`;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `cart`;
DROP TABLE IF EXISTS `favorites`;
DROP TABLE IF EXISTS `featured_products`;
DROP TABLE IF EXISTS `message_replies`;
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `user_addresses`;
DROP TABLE IF EXISTS `homepage_fallbacks`;
DROP TABLE IF EXISTS `announcements`;
DROP TABLE IF EXISTS `site_settings`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `role` enum('user','admin','superadmin') DEFAULT 'user',
  `status` enum('active','inactive') DEFAULT 'active',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `security_q1` varchar(255) DEFAULT NULL,
  `security_a1` varchar(255) DEFAULT NULL,
  `security_q2` varchar(255) DEFAULT NULL,
  `security_a2` varchar(255) DEFAULT NULL,
  `security_q3` varchar(255) DEFAULT NULL,
  `security_a3` varchar(255) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `sizes` text DEFAULT NULL,
  `image_main` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `featured_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `priority` int(11) DEFAULT 0,
  `bg_gradient` varchar(255) DEFAULT 'linear-gradient(135deg, #000000 0%, #1e293b 100%)',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `featured_products_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `homepage_fallbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campaign_name` varchar(100) NOT NULL,
  `badge_text` varchar(50) DEFAULT NULL,
  `hero_title` varchar(100) DEFAULT NULL,
  `hero_subtitle` varchar(255) DEFAULT NULL,
  `tagline` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category_pill` varchar(50) DEFAULT NULL,
  `action_headline` varchar(100) DEFAULT NULL,
  `btn1_text` varchar(50) DEFAULT NULL,
  `btn1_link` varchar(255) DEFAULT NULL,
  `btn2_text` varchar(50) DEFAULT NULL,
  `btn2_link` varchar(255) DEFAULT NULL,
  `num_buttons` int(11) DEFAULT 2,
  `bg_gradient` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `user_addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `street_address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category` varchar(50) DEFAULT 'Home Address',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `shipping_method` varchar(50) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `status` enum('pending','processing','shipped','delivered','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_archived` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(20) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `ticket_number` varchar(50) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('active','resolved') DEFAULT 'active',
  `admin_reply` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_archived_user` tinyint(1) DEFAULT 0,
  `is_archived_admin` tinyint(1) DEFAULT 0,
  `is_read_user` tinyint(1) DEFAULT 0,
  `is_read_admin` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ticket_number` (`ticket_number`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `message_replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `reply_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `message_id` (`message_id`),
  KEY `sender_id` (`sender_id`),
  CONSTRAINT `message_replies_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `message_replies_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `site_settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text NOT NULL,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(20) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `profile_picture`, `role`, `status`, `reset_token`, `reset_expires`, `security_q1`, `security_a1`, `security_q2`, `security_a2`, `security_q3`, `security_a3`, `last_login`, `created_at`) VALUES
(1, 'Admin', 'BlissAdmin', 'admin@bliss.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'admin', 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-01 09:00:00'),
(2, 'Super Admin', 'TsuperAdmin', 'superadmin@bliss.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'superadmin', 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-01 09:05:00'),
(3, 'John Doe', 'johndoe', 'john@bliss.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'user', 'active', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-01 09:10:00');

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category`, `brand`, `sizes`, `image_main`, `created_at`) VALUES
(1, 'Nike Air Max Phantom', 'Step into the future with lightweight comfort and a bold silhouette built for everyday wear.', 7000.00, 'Featured, Lifestyle', 'Nike', '{"US 7":0,"US 8":"0","US 9":"0","US 10":"0","US 11":"0","US 12":"0"}', '/php/Webdev/public/uploads/1777018682_1.png', '2026-03-01 09:00:00'),
(2, 'Nike Revolution Next', 'Lightweight mesh keeps every run breathable while responsive cushioning supports daily miles.', 4500.00, 'New Arrival, Best Seller, Running', 'Nike', '{"US 7":0,"US 8":"0","US 9":11,"US 10":8,"US 11":"5","US 12":"3"}', '/php/Webdev/public/uploads/1777010036_2.png', '2026-04-01 09:30:00'),
(3, 'Nike Dunk Low Retro', 'An 80s basketball icon reworked for the street with clean overlays and reliable comfort.', 5500.00, 'Featured, Lifestyle', 'Nike', '{"US 7":"0","US 8":"2","US 9":4,"US 10":7,"US 11":"10","US 12":"10"}', '/php/Webdev/public/uploads/1777010040_3.png', '2026-03-15 10:00:00'),
(5, 'Nike Pegasus 40', 'A springy running shoe with a familiar fit that is built to handle everyday training.', 6500.00, 'Best Seller, Running', 'Nike', '{"US 7":"4","US 8":"4","US 9":3,"US 10":"4","US 11":"4","US 12":0}', '/php/Webdev/public/uploads/1777010046_4.png', '2026-04-10 08:00:00');

INSERT INTO `featured_products` (`id`, `product_id`, `priority`, `bg_gradient`) VALUES
(1, 1, 0, 'linear-gradient(135deg, #0f172a 0%, #334155 100%)');

INSERT INTO `homepage_fallbacks` (`id`, `campaign_name`, `badge_text`, `hero_title`, `hero_subtitle`, `tagline`, `description`, `category_pill`, `action_headline`, `btn1_text`, `btn1_link`, `btn2_text`, `btn2_link`, `num_buttons`, `bg_gradient`, `is_active`) VALUES
(1, 'The BLISS Essential', 'The BLISS Collection', 'Step Into Tomorrow', 'Discover our newest collection of premium footwear.', 'Crafted for Excellence', 'Experience the balance of innovation, clean design, and all-day comfort.', 'Premium Quality', 'Find Your Pair', 'Shop Collection', '/catalog', 'Join Us', '/auth/register', 2, 'linear-gradient(135deg, #0f172a 0%, #334155 100%)', 0),
(2, 'Midnight Velocity', 'New Season Drop', 'Speed Meets Style', 'Engineered for those who never stop moving.', 'Precision Built', 'Every detail is tuned for energy return, stability, and confident daily wear.', 'Elite Performance', 'Ready to Fly?', 'Shop Now', '/catalog', '', '#', 1, 'linear-gradient(135deg, #ffdd00 0%, #ffa200 100%)', 1);

INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('announcement_bar_enabled', '1'),
('announcement_bg_color', '#000000');

INSERT INTO `announcements` (`id`, `message`, `is_active`, `created_at`) VALUES
(1, 'Welcome to Bliss', 1, '2026-04-20 08:00:00'),
(2, 'Shop Now', 1, '2026-04-20 08:05:00'),
(3, 'Feel free to explore', 1, '2026-04-20 08:10:00');

INSERT INTO `user_addresses` (`id`, `user_id`, `street_address`, `city`, `province`, `postal_code`, `is_default`, `created_at`, `category`) VALUES
(1, 3, '123 Demo Street', 'Taguig', 'NCR', '1200', 1, '2026-04-18 11:00:00', 'Home Address');

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `shipping_method`, `payment_method`, `shipping_address`, `status`, `created_at`, `is_archived`) VALUES
(1, 3, 12000.00, 'LBC', 'GCash', '123 Demo Street, Taguig, NCR 1200', 'processing', '2026-04-21 14:00:00', 0);

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `size`, `quantity`, `price`) VALUES
(1, 1, 5, 'US 9', 1, 6500.00),
(2, 1, 3, 'US 10', 1, 5500.00);

INSERT INTO `messages` (`id`, `user_id`, `ticket_number`, `subject`, `message`, `status`, `admin_reply`, `created_at`, `updated_at`, `is_archived_user`, `is_archived_admin`, `is_read_user`, `is_read_admin`) VALUES
(1, 3, 'TCK-DEMO01', 'Order Inquiry', 'Hello, can you confirm when my sample order will ship?', 'active', NULL, '2026-04-22 14:00:00', '2026-04-22 14:10:00', 0, 0, 1, 0);

INSERT INTO `message_replies` (`id`, `message_id`, `sender_id`, `reply_text`, `created_at`) VALUES
(1, 1, 1, 'We are checking your order now and will update you shortly.', '2026-04-22 14:05:00'),
(2, 1, 3, 'Thanks, I will wait for the update.', '2026-04-22 14:10:00');
