CREATE TABLE orders (
  customer_id INT AUTO_INCREMENT PRIMARY KEY,

  first_name VARCHAR(100) NOT NULL,
  last_name  VARCHAR(100) NOT NULL,
  phone      VARCHAR(20)  NOT NULL,
  address    VARCHAR(255) NOT NULL,
  email      VARCHAR(150) NOT NULL,

  chaos_croissant         INT NOT NULL DEFAULT 0,
  existential_eclair      INT NOT NULL DEFAULT 0,
  procrastination_cookie  INT NOT NULL DEFAULT 0,

  comments TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);