ALTER TABLE products
    ADD COLUMN IF NOT EXISTS brand VARCHAR(80) NOT NULL DEFAULT '',
    ADD COLUMN IF NOT EXISTS audience VARCHAR(20) NOT NULL DEFAULT 'men',
    ADD COLUMN IF NOT EXISTS on_sale TINYINT(1) NOT NULL DEFAULT 0;

UPDATE categories SET name = 'Sneakers' WHERE id = 1;

UPDATE products SET brand = 'Jordan', audience = 'women', on_sale = 0 WHERE id = 1;
UPDATE products SET brand = 'Jordan', audience = 'men', on_sale = 0 WHERE name = 'Air Jordan 1 Mid';
UPDATE products SET brand = 'Nike', audience = 'men', on_sale = 0 WHERE name = 'Nike Air Force 1';
UPDATE products SET brand = 'Nike', audience = 'women', on_sale = 1 WHERE name = 'Nike Dunk Low';
UPDATE products SET brand = 'Nike', audience = 'men', on_sale = 0 WHERE name = 'Nike Air Max 90';
UPDATE products SET brand = 'Nike', audience = 'men', on_sale = 1 WHERE name = 'Nike Air Max 270';
UPDATE products SET brand = 'Adidas', audience = 'women', on_sale = 0 WHERE name = 'Adidas Samba';
UPDATE products SET brand = 'Adidas', audience = 'kids', on_sale = 1 WHERE name = 'Adidas Superstar';
UPDATE products SET brand = 'New Balance', audience = 'women', on_sale = 0 WHERE name = 'New Balance 530';
UPDATE products SET brand = 'New Balance', audience = 'men', on_sale = 0 WHERE name = 'New Balance 550';
UPDATE products SET brand = 'Puma', audience = 'kids', on_sale = 1 WHERE name = 'Puma Suede';
UPDATE products SET brand = 'Converse', audience = 'kids', on_sale = 0 WHERE name = 'Converse Chuck Taylor';
