CREATE TABLE subscribers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100),
  last_name  VARCHAR(100),
  email      VARCHAR(150),
  subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
