CREATE TABLE IF NOT EXISTS stripe_customers (
 user_id INT NOT NULL PRIMARY KEY,
 customer_id VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS stripe_orders (
 order_id INT NOT NULL PRIMARY KEY,
 user_id INT NOT NULL,
 request_key VARCHAR(64) NOT NULL,
 intent_id VARCHAR(255) NULL UNIQUE,
 currency CHAR(3) NOT NULL,
 UNIQUE KEY user_request(user_id, request_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
