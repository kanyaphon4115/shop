INSERT INTO product_images (product_id, image)
SELECT p.id, gallery.image
FROM products p
JOIN (
    SELECT 'nike-air-force-1.png' main_image, 'nike-air-force-1.png' image UNION ALL
    SELECT 'nike-air-force-1.png', 'nike-air-force-1-top.png' UNION ALL
    SELECT 'nike-air-force-1.png', 'nike-air-force-1-heel.png' UNION ALL
    SELECT 'nike-dunk-low.png', 'nike-dunk-low.png' UNION ALL
    SELECT 'nike-dunk-low.png', 'nike-dunk-low-top.png' UNION ALL
    SELECT 'nike-dunk-low.png', 'nike-dunk-low-heel.png' UNION ALL
    SELECT 'nike-air-max-90.png', 'nike-air-max-90.png' UNION ALL
    SELECT 'nike-air-max-90.png', 'nike-air-max-90-top.png' UNION ALL
    SELECT 'nike-air-max-90.png', 'nike-air-max-90-heel.png' UNION ALL
    SELECT 'nike-air-max-270.png', 'nike-air-max-270.png' UNION ALL
    SELECT 'nike-air-max-270.png', 'nike-air-max-270-top.png' UNION ALL
    SELECT 'nike-air-max-270.png', 'nike-air-max-270-heel.png' UNION ALL
    SELECT 'adidas-samba.png', 'adidas-samba.png' UNION ALL
    SELECT 'adidas-samba.png', 'adidas-samba-top.png' UNION ALL
    SELECT 'adidas-samba.png', 'adidas-samba-heel.png' UNION ALL
    SELECT 'adidas-superstar.png', 'adidas-superstar.png' UNION ALL
    SELECT 'adidas-superstar.png', 'adidas-superstar-top.png' UNION ALL
    SELECT 'adidas-superstar.png', 'adidas-superstar-heel.png' UNION ALL
    SELECT 'new-balance-530.png', 'new-balance-530.png' UNION ALL
    SELECT 'new-balance-530.png', 'new-balance-530-top.png' UNION ALL
    SELECT 'new-balance-530.png', 'new-balance-530-heel.png' UNION ALL
    SELECT 'new-balance-550.png', 'new-balance-550.png' UNION ALL
    SELECT 'new-balance-550.png', 'new-balance-550-top.png' UNION ALL
    SELECT 'new-balance-550.png', 'new-balance-550-heel.png' UNION ALL
    SELECT 'puma-suede.png', 'puma-suede.png' UNION ALL
    SELECT 'puma-suede.png', 'puma-suede-top.png' UNION ALL
    SELECT 'puma-suede.png', 'puma-suede-heel.png' UNION ALL
    SELECT 'converse-chuck-taylor.png', 'converse-chuck-taylor.png' UNION ALL
    SELECT 'converse-chuck-taylor.png', 'converse-chuck-taylor-top.png' UNION ALL
    SELECT 'converse-chuck-taylor.png', 'converse-chuck-taylor-heel.png'
) gallery ON gallery.main_image = p.image
LEFT JOIN product_images existing ON existing.product_id = p.id AND existing.image = gallery.image
WHERE existing.id IS NULL;
