INSERT INTO product_images (product_id, image)
SELECT p.id, 'air-jordan-1-mid.png' FROM products p
WHERE p.name = 'Air Jordan 1 Mid'
  AND NOT EXISTS (SELECT 1 FROM product_images pi WHERE pi.product_id = p.id AND pi.image = 'air-jordan-1-mid.png');

INSERT INTO product_images (product_id, image)
SELECT p.id, 'air-jordan-1-mid-top.png' FROM products p
WHERE p.name = 'Air Jordan 1 Mid'
  AND NOT EXISTS (SELECT 1 FROM product_images pi WHERE pi.product_id = p.id AND pi.image = 'air-jordan-1-mid-top.png');

INSERT INTO product_images (product_id, image)
SELECT p.id, 'air-jordan-1-mid-heel.png' FROM products p
WHERE p.name = 'Air Jordan 1 Mid'
  AND NOT EXISTS (SELECT 1 FROM product_images pi WHERE pi.product_id = p.id AND pi.image = 'air-jordan-1-mid-heel.png');
