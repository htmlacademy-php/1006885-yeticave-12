CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE category (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(64) NOT NULL UNIQUE,
  class_name VARCHAR(32) NOT NULL UNIQUE
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  lot_id INT,
  rate_id INT,
  email VARCHAR(128) NOT NULL UNIQUE,
  password CHAR(64) NOT NULL,
  name VARCHAR(128) NOT NULL,
  contacts TEXT,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE lots (
  id INT AUTO_INCREMENT PRIMARY KEY,
  author_id INT,
  winner_id INT,
  category_id INT,
  name VARCHAR(64) NOT NULL,
  description VARCHAR(255),
  img_url VARCHAR(128),
  price DECIMAL(10,2) NOT NULL,
  rate_step TINYINT NOT NULL,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  dt_exp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (author_id) REFERENCES users(id),
  FOREIGN KEY (winner_id) REFERENCES users(id),
  FOREIGN KEY (category_id) REFERENCES category(id)
);

CREATE TABLE rates (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  lot_id INT,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  price DECIMAL NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (lot_id) REFERENCES lots(id)
);

ALTER TABLE users ADD FOREIGN KEY (lot_id) REFERENCES lots(id);
ALTER TABLE users ADD FOREIGN KEY (rate_id) REFERENCES rates(id);

CREATE INDEX user_email ON users(email);
CREATE INDEX lot_name ON lots(name);
CREATE INDEX lot_price ON lots(price);
CREATE INDEX lot_dt_add ON lots(dt_add);
CREATE INDEX lot_dt_exp ON lots(dt_exp);
