SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = "+08:00";

SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE notifications;
TRUNCATE TABLE reviews;
TRUNCATE TABLE payments;
TRUNCATE TABLE order_items;
TRUNCATE TABLE orders;
TRUNCATE TABLE cart;
TRUNCATE TABLE addresses;
TRUNCATE TABLE products;
TRUNCATE TABLE categories;
TRUNCATE TABLE users;

SET FOREIGN_KEY_CHECKS = 1;

START TRANSACTION;

INSERT INTO categories (category_id, category_name) VALUES
    (1, 'Accessories'),
    (2, 'Case'),
    (3, 'Cooling System'),
    (4, 'CPU'),
    (5, 'GPU'),
    (6, 'Motherboard'),
    (7, 'PSU'),
    (8, 'RAM'),
    (9, 'Storage');

INSERT INTO products (product_id, category_id, product_name, description, price, stock, image, status, is_deleted, created_at) VALUES
    (1, 1, 'Mechanical Keyboard', 'RGB mechanical keyboard with blue switches for gaming and office use.', 1999.00, 35, 'mechanical-keyboard.jpg', 'active', 0, '2025-10-01 09:00:00'),
    (2, 1, 'Gaming Mouse', 'Ergonomic wired gaming mouse with adjustable DPI.', 899.00, 60, 'gaming-mouse.jpg', 'active', 0, '2025-10-01 09:05:00'),
    (3, 1, 'USB-C Multiport Hub', 'Compact USB-C hub with HDMI, USB 3.0, and card reader.', 1299.00, 40, 'usb-c-hub.jpg', 'active', 0, '2025-10-01 09:10:00'),
    (4, 2, 'ATX Tempered Glass Case', 'Full ATX case with tempered glass side panel and cable management.', 3499.00, 18, 'atx-tempered-case.jpg', 'active', 0, '2025-10-02 10:00:00'),
    (5, 2, 'Mini-ITX Compact Case', 'Small form factor case suitable for space-saving PC builds.', 2899.00, 14, 'mini-itx-case.jpg', 'active', 0, '2025-10-02 10:05:00'),
    (6, 2, 'Mid Tower Airflow Case', 'Mid tower case with mesh front panel for better airflow.', 2599.00, 22, 'mid-tower-airflow-case.jpg', 'active', 0, '2025-10-02 10:10:00'),
    (7, 3, '120mm RGB Fan Pack', 'Triple 120mm RGB fan set for improved airflow and style.', 1499.00, 50, 'rgb-fan-pack.jpg', 'active', 0, '2025-10-03 11:00:00'),
    (8, 3, '240mm AIO Liquid Cooler', 'All-in-one liquid CPU cooler with 240mm radiator.', 4599.00, 12, 'aio-liquid-cooler.jpg', 'active', 0, '2025-10-03 11:05:00'),
    (9, 3, 'Tower CPU Air Cooler', 'Affordable tower-style CPU air cooler for mainstream processors.', 1999.00, 25, 'tower-air-cooler.jpg', 'active', 0, '2025-10-03 11:10:00'),
    (10, 4, 'AMD Ryzen 5 5600', '6-core AM4 processor for budget and midrange gaming builds.', 6999.00, 16, 'ryzen-5-5600.jpg', 'active', 0, '2025-10-04 12:00:00'),
    (11, 4, 'Intel Core i5-12400F', '6-core LGA1700 processor with strong gaming performance.', 7999.00, 15, 'i5-12400f.jpg', 'active', 0, '2025-10-04 12:05:00'),
    (12, 4, 'AMD Ryzen 7 5700X', '8-core AM4 processor for gaming, streaming, and productivity.', 9999.00, 10, 'ryzen-7-5700x.jpg', 'active', 0, '2025-10-04 12:10:00'),
    (13, 5, 'NVIDIA RTX 4060 8GB', '8GB graphics card for 1080p gaming and content creation.', 18999.00, 8, 'rtx-4060.jpg', 'active', 0, '2025-10-05 13:00:00'),
    (14, 5, 'AMD Radeon RX 7600 8GB', '8GB Radeon GPU for high-performance 1080p gaming.', 15999.00, 9, 'rx-7600.jpg', 'active', 0, '2025-10-05 13:05:00'),
    (15, 5, 'GTX 1660 Super 6GB', 'Affordable 6GB graphics card for entry-level gaming.', 9499.00, 11, 'gtx-1660-super.jpg', 'active', 0, '2025-10-05 13:10:00'),
    (16, 6, 'B550M AM4 Motherboard', 'Micro-ATX AM4 motherboard with M.2 support and PCIe expansion.', 5399.00, 20, 'b550m-motherboard.jpg', 'active', 0, '2025-10-06 14:00:00'),
    (17, 6, 'B660M LGA1700 Motherboard', 'Micro-ATX Intel motherboard for 12th generation processors.', 5999.00, 18, 'b660m-motherboard.jpg', 'active', 0, '2025-10-06 14:05:00'),
    (18, 6, 'A520M Entry Motherboard', 'Budget AM4 motherboard for basic office and gaming systems.', 3499.00, 24, 'a520m-motherboard.jpg', 'active', 0, '2025-10-06 14:10:00'),
    (19, 7, '550W 80+ Bronze PSU', 'Reliable 550W power supply for budget PC builds.', 2499.00, 30, '550w-bronze-psu.jpg', 'active', 0, '2025-10-07 15:00:00'),
    (20, 7, '650W 80+ Gold PSU', 'Efficient 650W power supply for midrange systems.', 3999.00, 20, '650w-gold-psu.jpg', 'active', 0, '2025-10-07 15:05:00'),
    (21, 7, '750W Modular Gold PSU', 'Fully modular 750W gold-rated PSU for high-end PC builds.', 5499.00, 13, '750w-modular-psu.jpg', 'active', 0, '2025-10-07 15:10:00'),
    (22, 8, '16GB DDR4 3200 RAM', '2x8GB DDR4 memory kit suitable for gaming and school work.', 2499.00, 45, '16gb-ddr4-ram.jpg', 'active', 0, '2025-10-08 16:00:00'),
    (23, 8, '32GB DDR4 3200 RAM', '2x16GB DDR4 memory kit for multitasking and editing.', 4499.00, 26, '32gb-ddr4-ram.jpg', 'active', 0, '2025-10-08 16:05:00'),
    (24, 8, '16GB DDR5 5600 RAM', 'Fast 16GB DDR5 memory kit for newer platforms.', 3999.00, 19, '16gb-ddr5-ram.jpg', 'active', 0, '2025-10-08 16:10:00'),
    (25, 9, '500GB NVMe SSD', 'Fast 500GB NVMe SSD for operating systems and applications.', 1999.00, 55, '500gb-nvme-ssd.jpg', 'active', 0, '2025-10-09 17:00:00'),
    (26, 9, '1TB NVMe SSD', 'High-speed 1TB NVMe SSD for games, files, and software.', 3999.00, 38, '1tb-nvme-ssd.jpg', 'active', 0, '2025-10-09 17:05:00'),
    (27, 9, '2TB SATA HDD', 'Large 2TB hard drive for backup and mass storage.', 2999.00, 28, '2tb-sata-hdd.jpg', 'active', 0, '2025-10-09 17:10:00');

INSERT INTO users (user_id, first_name, last_name, email, password, current_address_id, profile_pic, role, created_at, is_active) VALUES
    (1, 'Admin', 'Account', 'admin@smarttech.test', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, NULL, 'admin', '2025-09-01 08:00:00', 1),
    (2, 'Maria', 'Santos', 'maria.santos@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'maria.jpg', 'user', '2025-09-05 09:20:00', 1),
    (3, 'Juan', 'Dela Cruz', 'juan.delacruz@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'juan.jpg', 'user', '2025-09-08 10:10:00', 1),
    (4, 'Ana', 'Reyes', 'ana.reyes@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'ana.jpg', 'user', '2025-09-12 13:45:00', 1),
    (5, 'Mark', 'Garcia', 'mark.garcia@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'mark.jpg', 'user', '2025-09-18 15:30:00', 1),
    (6, 'Bea', 'Lim', 'bea.lim@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'bea.jpg', 'user', '2025-10-02 11:15:00', 1),
    (7, 'Paolo', 'Cruz', 'paolo.cruz@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'paolo.jpg', 'user', '2025-10-15 14:05:00', 1),
    (8, 'Nicole', 'Torres', 'nicole.torres@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'nicole.jpg', 'user', '2025-11-01 08:35:00', 1),
    (9, 'Ryan', 'Mendoza', 'ryan.mendoza@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'ryan.jpg', 'user', '2025-11-20 16:50:00', 1),
    (10, 'Leah', 'Ramos', 'leah.ramos@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'leah.jpg', 'user', '2025-12-03 18:05:00', 1),
    (11, 'Carlo', 'Villanueva', 'carlo.villanueva@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'carlo.jpg', 'user', '2025-12-16 10:25:00', 1),
    (12, 'Sofia', 'Navarro', 'sofia.navarro@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.', NULL, 'sofia.jpg', 'user', '2026-01-08 12:40:00', 0);

INSERT INTO addresses (address_id, user_id, full_name, phone, address_line, city, province, postal_code, created_at) VALUES
    (1, 2, 'Maria Santos', '09171234567', 'Blk 4 Lot 12 Green Estate', 'Imus', 'Cavite', '4103', '2025-09-05 09:30:00'),
    (2, 3, 'Juan Dela Cruz', '09181234567', 'Unit 502 Mabini Heights, Ermita', 'Manila', 'Metro Manila', '1000', '2025-09-08 10:20:00'),
    (3, 4, 'Ana Reyes', '09191234567', '45 Rizal Street, Brgy. San Antonio', 'San Pedro', 'Laguna', '4023', '2025-09-12 13:55:00'),
    (4, 5, 'Mark Garcia', '09201234567', '88 P. Burgos Avenue', 'Batangas City', 'Batangas', '4200', '2025-09-18 15:40:00'),
    (5, 6, 'Bea Lim', '09211234567', '17 Malolos Crossing', 'Malolos', 'Bulacan', '3000', '2025-10-02 11:25:00'),
    (6, 7, 'Paolo Cruz', '09221234567', '12 Mango Avenue, Lahug', 'Cebu City', 'Cebu', '6000', '2025-10-15 14:15:00'),
    (7, 8, 'Nicole Torres', '09231234567', 'Block 8 Lot 5 Matina Aplaya', 'Davao City', 'Davao del Sur', '8000', '2025-11-01 08:45:00'),
    (8, 9, 'Ryan Mendoza', '09241234567', '9 Delgado Street', 'Iloilo City', 'Iloilo', '5000', '2025-11-20 17:00:00'),
    (9, 10, 'Leah Ramos', '09251234567', '22 Friendship Highway', 'Angeles City', 'Pampanga', '2009', '2025-12-03 18:15:00'),
    (10, 11, 'Carlo Villanueva', '09261234567', '31 Ortigas Extension, San Isidro', 'Cainta', 'Rizal', '1900', '2025-12-16 10:35:00'),
    (11, 12, 'Sofia Navarro', '09271234567', '75 Quezon Avenue', 'Lucena City', 'Quezon', '4301', '2026-01-08 12:50:00');

UPDATE users SET current_address_id = 1 WHERE user_id = 2;
UPDATE users SET current_address_id = 2 WHERE user_id = 3;
UPDATE users SET current_address_id = 3 WHERE user_id = 4;
UPDATE users SET current_address_id = 4 WHERE user_id = 5;
UPDATE users SET current_address_id = 5 WHERE user_id = 6;
UPDATE users SET current_address_id = 6 WHERE user_id = 7;
UPDATE users SET current_address_id = 7 WHERE user_id = 8;
UPDATE users SET current_address_id = 8 WHERE user_id = 9;
UPDATE users SET current_address_id = 9 WHERE user_id = 10;
UPDATE users SET current_address_id = 10 WHERE user_id = 11;
UPDATE users SET current_address_id = 11 WHERE user_id = 12;

INSERT INTO orders (order_id, user_id, address_id, order_date, total_amount, order_status) VALUES
    (1, 2, 1, '2025-11-12 10:25:00', 19395.00, 'delivered'),
    (2, 3, 2, '2025-11-28 15:40:00', 4197.00, 'delivered'),
    (3, 4, 3, '2025-12-05 09:15:00', 25597.00, 'delivered'),
    (4, 5, 4, '2025-12-18 18:20:00', 21498.00, 'cancelled'),
    (5, 6, 5, '2026-01-04 11:05:00', 10497.00, 'delivered'),
    (6, 7, 6, '2026-01-11 13:30:00', 21996.00, 'delivered'),
    (7, 8, 7, '2026-01-23 16:45:00', 15497.00, 'processing'),
    (8, 9, 8, '2026-02-02 08:55:00', 17596.00, 'delivered'),
    (9, 10, 9, '2026-02-10 14:10:00', 12997.00, 'delivered'),
    (10, 11, 10, '2026-02-17 19:25:00', 10896.00, 'shipped'),
    (11, 12, 11, '2026-02-25 12:00:00', 18999.00, 'cancelled'),
    (12, 2, 1, '2026-03-03 10:05:00', 9296.00, 'delivered'),
    (13, 3, 2, '2026-03-09 17:35:00', 23897.00, 'delivered'),
    (14, 4, 3, '2026-03-15 09:50:00', 19097.00, 'delivered'),
    (15, 5, 4, '2026-03-22 20:05:00', 2898.00, 'pending'),
    (16, 6, 5, '2026-04-01 11:40:00', 16496.00, 'delivered'),
    (17, 7, 6, '2026-04-07 15:10:00', 32496.00, 'delivered'),
    (18, 8, 7, '2026-04-13 13:25:00', 6097.00, 'processing'),
    (19, 9, 8, '2026-04-20 18:00:00', 17997.00, 'delivered'),
    (20, 10, 9, '2026-04-27 10:30:00', 14497.00, 'cancelled'),
    (21, 11, 10, '2026-05-03 09:20:00', 25997.00, 'delivered'),
    (22, 12, 11, '2026-05-08 14:55:00', 11097.00, 'delivered'),
    (23, 2, 1, '2026-05-14 16:05:00', 19397.00, 'shipped'),
    (24, 3, 2, '2026-05-19 11:15:00', 10897.00, 'delivered'),
    (25, 4, 3, '2026-05-26 19:45:00', 3697.00, 'pending'),
    (26, 5, 4, '2026-06-01 08:45:00', 28497.00, 'delivered'),
    (27, 6, 5, '2026-06-04 12:35:00', 16897.00, 'processing'),
    (28, 7, 6, '2026-06-07 17:20:00', 20995.00, 'delivered'),
    (29, 8, 7, '2026-06-09 20:10:00', 22497.00, 'shipped'),
    (30, 9, 8, '2026-06-10 10:15:00', 4598.00, 'cancelled');

INSERT INTO order_items (order_item_id, order_id, product_id, quantity, price, subtotal) VALUES
    (1, 1, 10, 1, 6999.00, 6999.00),
    (2, 1, 16, 1, 5399.00, 5399.00),
    (3, 1, 22, 2, 2499.00, 4998.00),
    (4, 1, 25, 1, 1999.00, 1999.00),
    (5, 2, 1, 1, 1999.00, 1999.00),
    (6, 2, 2, 1, 899.00, 899.00),
    (7, 2, 3, 1, 1299.00, 1299.00),
    (8, 3, 13, 1, 18999.00, 18999.00),
    (9, 3, 20, 1, 3999.00, 3999.00),
    (10, 3, 6, 1, 2599.00, 2599.00),
    (11, 4, 14, 1, 15999.00, 15999.00),
    (12, 4, 21, 1, 5499.00, 5499.00),
    (13, 5, 26, 1, 3999.00, 3999.00),
    (14, 5, 23, 1, 4499.00, 4499.00),
    (15, 5, 9, 1, 1999.00, 1999.00),
    (16, 6, 11, 1, 7999.00, 7999.00),
    (17, 6, 17, 1, 5999.00, 5999.00),
    (18, 6, 24, 2, 3999.00, 7998.00),
    (19, 7, 15, 1, 9499.00, 9499.00),
    (20, 7, 18, 1, 3499.00, 3499.00),
    (21, 7, 19, 1, 2499.00, 2499.00),
    (22, 8, 8, 1, 4599.00, 4599.00),
    (23, 8, 7, 2, 1499.00, 2998.00),
    (24, 8, 12, 1, 9999.00, 9999.00),
    (25, 9, 4, 1, 3499.00, 3499.00),
    (26, 9, 21, 1, 5499.00, 5499.00),
    (27, 9, 26, 1, 3999.00, 3999.00),
    (28, 10, 5, 1, 2899.00, 2899.00),
    (29, 10, 20, 1, 3999.00, 3999.00),
    (30, 10, 25, 2, 1999.00, 3998.00),
    (31, 11, 13, 1, 18999.00, 18999.00),
    (32, 12, 3, 1, 1299.00, 1299.00),
    (33, 12, 22, 2, 2499.00, 4998.00),
    (34, 12, 27, 1, 2999.00, 2999.00),
    (35, 13, 14, 1, 15999.00, 15999.00),
    (36, 13, 16, 1, 5399.00, 5399.00),
    (37, 13, 19, 1, 2499.00, 2499.00),
    (38, 14, 12, 1, 9999.00, 9999.00),
    (39, 14, 8, 1, 4599.00, 4599.00),
    (40, 14, 23, 1, 4499.00, 4499.00),
    (41, 15, 1, 1, 1999.00, 1999.00),
    (42, 15, 2, 1, 899.00, 899.00),
    (43, 16, 10, 1, 6999.00, 6999.00),
    (44, 16, 18, 1, 3499.00, 3499.00),
    (45, 16, 20, 1, 3999.00, 3999.00),
    (46, 16, 25, 1, 1999.00, 1999.00),
    (47, 17, 13, 1, 18999.00, 18999.00),
    (48, 17, 21, 1, 5499.00, 5499.00),
    (49, 17, 24, 2, 3999.00, 7998.00),
    (50, 18, 6, 1, 2599.00, 2599.00),
    (51, 18, 7, 1, 1499.00, 1499.00),
    (52, 18, 9, 1, 1999.00, 1999.00),
    (53, 19, 11, 1, 7999.00, 7999.00),
    (54, 19, 17, 1, 5999.00, 5999.00),
    (55, 19, 26, 1, 3999.00, 3999.00),
    (56, 20, 15, 1, 9499.00, 9499.00),
    (57, 20, 19, 1, 2499.00, 2499.00),
    (58, 20, 22, 1, 2499.00, 2499.00),
    (59, 21, 14, 1, 15999.00, 15999.00),
    (60, 21, 21, 1, 5499.00, 5499.00),
    (61, 21, 23, 1, 4499.00, 4499.00),
    (62, 22, 4, 1, 3499.00, 3499.00),
    (63, 22, 8, 1, 4599.00, 4599.00),
    (64, 22, 27, 1, 2999.00, 2999.00),
    (65, 23, 12, 1, 9999.00, 9999.00),
    (66, 23, 16, 1, 5399.00, 5399.00),
    (67, 23, 24, 1, 3999.00, 3999.00),
    (68, 24, 5, 1, 2899.00, 2899.00),
    (69, 24, 20, 1, 3999.00, 3999.00),
    (70, 24, 26, 1, 3999.00, 3999.00),
    (71, 25, 2, 1, 899.00, 899.00),
    (72, 25, 3, 1, 1299.00, 1299.00),
    (73, 25, 7, 1, 1499.00, 1499.00),
    (74, 26, 13, 1, 18999.00, 18999.00),
    (75, 26, 21, 1, 5499.00, 5499.00),
    (76, 26, 26, 1, 3999.00, 3999.00),
    (77, 27, 10, 1, 6999.00, 6999.00),
    (78, 27, 16, 1, 5399.00, 5399.00),
    (79, 27, 23, 1, 4499.00, 4499.00),
    (80, 28, 11, 1, 7999.00, 7999.00),
    (81, 28, 17, 1, 5999.00, 5999.00),
    (82, 28, 22, 2, 2499.00, 4998.00),
    (83, 28, 25, 1, 1999.00, 1999.00),
    (84, 29, 14, 1, 15999.00, 15999.00),
    (85, 29, 19, 1, 2499.00, 2499.00),
    (86, 29, 24, 1, 3999.00, 3999.00),
    (87, 30, 1, 1, 1999.00, 1999.00),
    (88, 30, 6, 1, 2599.00, 2599.00);

INSERT INTO payments (payment_id, order_id, payment_method, amount, payment_status, payment_date) VALUES
    (1, 1, 'GCash', 19395.00, 'paid', '2025-11-12 10:40:00'),
    (2, 2, 'COD', 4197.00, 'paid', '2025-11-28 15:55:00'),
    (3, 3, 'Maya', 25597.00, 'paid', '2025-12-05 09:30:00'),
    (4, 4, 'Credit/Debit Card', 21498.00, 'refunded', '2025-12-19 18:20:00'),
    (5, 5, 'GCash', 10497.00, 'paid', '2026-01-04 11:20:00'),
    (6, 6, 'COD', 21996.00, 'paid', '2026-01-11 13:45:00'),
    (7, 7, 'Maya', 15497.00, 'paid', '2026-01-23 17:00:00'),
    (8, 8, 'GCash', 17596.00, 'paid', '2026-02-02 09:10:00'),
    (9, 9, 'Credit/Debit Card', 12997.00, 'paid', '2026-02-10 14:25:00'),
    (10, 10, 'Maya', 10896.00, 'paid', '2026-02-17 19:40:00'),
    (11, 11, 'COD', 18999.00, 'failed', '2026-02-25 12:00:00'),
    (12, 12, 'GCash', 9296.00, 'paid', '2026-03-03 10:20:00'),
    (13, 13, 'Maya', 23897.00, 'paid', '2026-03-09 17:50:00'),
    (14, 14, 'Credit/Debit Card', 19097.00, 'paid', '2026-03-15 10:05:00'),
    (15, 15, 'COD', 2898.00, 'pending', '2026-03-22 20:05:00'),
    (16, 16, 'GCash', 16496.00, 'paid', '2026-04-01 11:55:00'),
    (17, 17, 'Maya', 32496.00, 'paid', '2026-04-07 15:25:00'),
    (18, 18, 'Credit/Debit Card', 6097.00, 'paid', '2026-04-13 13:40:00'),
    (19, 19, 'COD', 17997.00, 'paid', '2026-04-20 18:15:00'),
    (20, 20, 'GCash', 14497.00, 'refunded', '2026-04-28 10:30:00'),
    (21, 21, 'Credit/Debit Card', 25997.00, 'paid', '2026-05-03 09:35:00'),
    (22, 22, 'Maya', 11097.00, 'paid', '2026-05-08 15:10:00'),
    (23, 23, 'GCash', 19397.00, 'paid', '2026-05-14 16:20:00'),
    (24, 24, 'COD', 10897.00, 'paid', '2026-05-19 11:30:00'),
    (25, 25, 'GCash', 3697.00, 'pending', '2026-05-26 19:45:00'),
    (26, 26, 'Maya', 28497.00, 'paid', '2026-06-01 09:00:00'),
    (27, 27, 'Credit/Debit Card', 16897.00, 'paid', '2026-06-04 12:50:00'),
    (28, 28, 'GCash', 20995.00, 'paid', '2026-06-07 17:35:00'),
    (29, 29, 'Maya', 22497.00, 'paid', '2026-06-09 20:25:00'),
    (30, 30, 'COD', 4598.00, 'failed', '2026-06-10 10:15:00');

INSERT INTO reviews (review_id, user_id, product_id, comment, review_date, rating) VALUES
    (1, 2, 10, 'Very good processor for the price. My PC feels much faster now.', '2025-11-20 12:10:00', 5),
    (2, 2, 16, 'Easy to install and enough ports for my build.', '2025-11-20 12:12:00', 4),
    (3, 3, 1, 'Keyboard feels solid and the RGB looks nice.', '2025-12-01 09:30:00', 5),
    (4, 3, 2, 'Good mouse for gaming, but the cable could be softer.', '2025-12-01 09:35:00', 4),
    (5, 4, 13, 'Great 1080p performance and quiet fans.', '2025-12-12 15:20:00', 5),
    (6, 6, 26, 'Fast storage and easy to set up.', '2026-01-09 11:50:00', 5),
    (7, 6, 23, 'Good RAM for multitasking and editing school projects.', '2026-01-09 11:55:00', 4),
    (8, 7, 11, 'Strong CPU for the price. Works well with my board.', '2026-01-18 13:15:00', 5),
    (9, 9, 8, 'Temperatures improved after installing this cooler.', '2026-02-08 17:45:00', 5),
    (10, 10, 4, 'Spacious case and clean cable management options.', '2026-02-16 10:05:00', 4),
    (11, 2, 27, 'Useful for storing videos and backups.', '2026-03-10 18:25:00', 4),
    (12, 3, 14, 'Smooth FPS on most games at 1080p.', '2026-03-18 14:40:00', 5),
    (13, 4, 12, 'Excellent CPU for editing and gaming.', '2026-03-24 19:00:00', 5),
    (14, 6, 20, 'Stable PSU and good efficiency.', '2026-04-08 12:20:00', 4),
    (15, 7, 21, 'Modular cables made the build cleaner.', '2026-04-15 16:30:00', 5),
    (16, 9, 17, 'Reliable motherboard for Intel build.', '2026-04-27 09:10:00', 4),
    (17, 11, 14, 'Good GPU, delivery was smooth.', '2026-05-09 20:20:00', 5),
    (18, 12, 8, 'Cooling performance is good, but installation took time.', '2026-05-15 10:45:00', 4),
    (19, 5, 13, 'Very powerful card for my gaming setup.', '2026-06-05 15:05:00', 5),
    (20, 7, 22, 'RAM works perfectly with XMP enabled.', '2026-06-10 11:25:00', 5);

INSERT INTO cart (cart_id, user_id, product_id, quantity, created_at) VALUES
    (1, 2, 7, 1, '2026-06-11 08:10:00'),
    (2, 3, 24, 1, '2026-06-11 08:15:00'),
    (3, 4, 20, 1, '2026-06-11 08:20:00'),
    (4, 5, 25, 2, '2026-06-11 08:25:00'),
    (5, 6, 2, 1, '2026-06-11 08:30:00'),
    (6, 8, 11, 1, '2026-06-11 08:35:00'),
    (7, 10, 18, 1, '2026-06-11 08:40:00'),
    (8, 11, 3, 1, '2026-06-11 08:45:00');

INSERT INTO notifications (notification_id, user_id, role_target, title, message, type, is_read, created_at) VALUES
    (1, NULL, 'admin', 'New Order Received', 'Order #28 has been placed by Paolo Cruz.', 'new_order', 0, '2026-06-07 17:20:00'),
    (2, 7, 'user', 'Order Delivered', 'Your order #28 has been marked as delivered.', 'order_status', 0, '2026-06-09 09:00:00'),
    (3, NULL, 'admin', 'Payment Confirmed', 'Payment for order #29 using Maya has been recorded.', 'payment', 0, '2026-06-09 20:25:00'),
    (4, 8, 'user', 'Order Shipped', 'Your order #29 is now shipped.', 'shipping', 0, '2026-06-10 08:15:00'),
    (5, NULL, 'admin', 'Cancelled Order', 'Order #30 was cancelled and payment failed.', 'order_status', 0, '2026-06-10 10:20:00'),
    (6, 2, 'user', 'Cart Reminder', 'You still have an RGB fan pack in your cart.', 'cart', 1, '2026-06-11 08:10:00'),
    (7, NULL, 'admin', 'New Review Posted', 'Paolo Cruz posted a 5-star review.', 'review', 0, '2026-06-10 11:25:00'),
    (8, NULL, 'admin', 'Low Stock Alert', 'RTX 4060 8GB has limited stock remaining.', 'stock', 0, '2026-06-11 09:00:00'),
    (9, 5, 'user', 'Order Delivered', 'Your order #26 has been delivered successfully.', 'order_status', 1, '2026-06-03 10:00:00'),
    (10, 12, 'user', 'Account Notice', 'Your account is inactive. Please contact support if this is unexpected.', 'account', 0, '2026-06-05 14:00:00');

COMMIT;
