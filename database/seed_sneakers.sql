ALTER TABLE products
    ADD COLUMN IF NOT EXISTS rating DECIMAL(2,1) NOT NULL DEFAULT 0.0,
    ADD COLUMN IF NOT EXISTS sizes VARCHAR(100) NOT NULL DEFAULT '["S","M","L"]',
    ADD COLUMN IF NOT EXISTS sold_count INT NOT NULL DEFAULT 0;

UPDATE products SET rating = 4.9, sizes = '["S","M","L"]', sold_count = 120 WHERE id = 1;

INSERT INTO products (name, price, image, category_id, description, rating, sizes, sold_count)
SELECT 'Air Jordan 1 Mid', 2400.00, 'air-jordan-1-mid.png', 1, 'A timeless mid-top basketball silhouette with supportive cushioning and everyday street style.', 4.9, '["S","M","L"]', 120
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'Air Jordan 1 Mid');

INSERT INTO products (name, price, image, category_id, description, rating, sizes, sold_count)
SELECT 'Nike Air Force 1', 2200.00, 'nike-air-force-1.png', 1, 'A clean leather court sneaker with classic proportions, durable construction and soft cushioning.', 4.8, '["S","M","L"]', 95
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'Nike Air Force 1');

INSERT INTO products (name, price, image, category_id, description, rating, sizes, sold_count)
SELECT 'Nike Dunk Low', 2100.00, 'nike-dunk-low.png', 1, 'A versatile low-top sneaker inspired by vintage basketball footwear and modern everyday comfort.', 4.7, '["S","M","L"]', 88
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'Nike Dunk Low');

INSERT INTO products (name, price, image, category_id, description, rating, sizes, sold_count)
SELECT 'Nike Air Max 90', 2600.00, 'nike-air-max-90.png', 1, 'A retro running icon featuring layered panels, a visible air unit and a comfortable padded collar.', 4.9, '["S","M","L"]', 132
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'Nike Air Max 90');

INSERT INTO products (name, price, image, category_id, description, rating, sizes, sold_count)
SELECT 'Nike Air Max 270', 2850.00, 'nike-air-max-270.png', 1, 'A modern lifestyle runner with breathable mesh and generous heel cushioning for all-day wear.', 4.8, '["S","M","L"]', 104
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'Nike Air Max 270');

INSERT INTO products (name, price, image, category_id, description, rating, sizes, sold_count)
SELECT 'Adidas Samba', 2350.00, 'adidas-samba.png', 1, 'A low-profile terrace sneaker with a smooth leather upper, suede detailing and a gum-style sole.', 4.9, '["S","M","L"]', 146
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'Adidas Samba');

INSERT INTO products (name, price, image, category_id, description, rating, sizes, sold_count)
SELECT 'Adidas Superstar', 2250.00, 'adidas-superstar.png', 1, 'A classic low-top court shoe recognized for its durable leather upper and protective shell-style toe.', 4.7, '["S","M","L"]', 79
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'Adidas Superstar');

INSERT INTO products (name, price, image, category_id, description, rating, sizes, sold_count)
SELECT 'New Balance 530', 1950.00, 'new-balance-530.png', 1, 'A breathable retro runner combining mesh, synthetic overlays and lightweight everyday cushioning.', 4.9, '["S","M","L"]', 110
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'New Balance 530');

INSERT INTO products (name, price, image, category_id, description, rating, sizes, sold_count)
SELECT 'New Balance 550', 2450.00, 'new-balance-550.png', 1, 'A heritage basketball-inspired low top with structured leather panels and a supportive rubber outsole.', 4.8, '["S","M","L"]', 91
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'New Balance 550');

INSERT INTO products (name, price, image, category_id, description, rating, sizes, sold_count)
SELECT 'Puma Suede', 1850.00, 'puma-suede.png', 1, 'A streamlined suede classic with a soft upper, padded collar and dependable rubber cupsole.', 4.6, '["S","M","L"]', 68
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'Puma Suede');

INSERT INTO products (name, price, image, category_id, description, rating, sizes, sold_count)
SELECT 'Converse Chuck Taylor', 1750.00, 'converse-chuck-taylor.png', 1, 'An iconic canvas high top with a rubber toe cap, flexible sole and unmistakable casual profile.', 4.8, '["S","M","L"]', 155
WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = 'Converse Chuck Taylor');
