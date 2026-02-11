-- =============================================================================
-- 1. PRODUCTS (master list) - no quantity, no enquiry_id. Used for dropdown.
--    On product select, fetch product_name, category, price from this table.
-- =============================================================================

CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(150) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`product_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- If your existing `products` table has enquiry_id and quantity (old Test List data):
-- 1. Create enquiry_products (above).
-- 2. Migrate: INSERT INTO enquiry_products (enquiry_id, product_id, quantity, total, status)
--    SELECT enquiry_id, product_id, quantity, (price * quantity), status FROM products WHERE enquiry_id IS NOT NULL;
-- 3. Then alter products to match master structure (remove enquiry_id, quantity):
--    ALTER TABLE `products` DROP COLUMN `enquiry_id`, DROP COLUMN `quantity`;
--    ALTER TABLE `products` ADD COLUMN `created_at` datetime DEFAULT current_timestamp() AFTER `status`;  -- if missing

-- =============================================================================
-- 2. ENQUIRY_PRODUCTS (Test List grid) - one row per product line per enquiry.
--    Saves: enquiry_id, product_id (FK), quantity, total, status.
--    product_name, category, price are fetched from products when loading grid.
-- =============================================================================

CREATE TABLE IF NOT EXISTS `enquiry_products` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `enquiry_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `total` decimal(12,2) DEFAULT NULL COMMENT 'price * quantity (from products.price)',
  `status` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_enquiry_id` (`enquiry_id`),
  KEY `idx_product_id` (`product_id`),
  CONSTRAINT `fk_enquiry_products_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Test list product lines per enquiry';

-- Example: get grid rows for an enquiry (join with products for name, category, price)
-- SELECT ep.id, ep.enquiry_id, ep.product_id, p.product_name, p.category, p.price, ep.quantity, ep.total, ep.status
-- FROM enquiry_products ep
-- INNER JOIN products p ON p.product_id = ep.product_id
-- WHERE ep.enquiry_id = 146
-- ORDER BY ep.id;

-- Example: insert a new line
-- INSERT INTO enquiry_products (enquiry_id, product_id, quantity, total, status)
-- VALUES (146, 1, 100, 5000.00, 1);

-- Example: update a line
-- UPDATE enquiry_products SET quantity = 200, total = 10000.00, status = 1, updated_at = NOW()
-- WHERE id = 1 AND enquiry_id = 146;

-- Example: delete lines for an enquiry not in incoming list (application does this by id)
