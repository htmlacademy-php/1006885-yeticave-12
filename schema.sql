CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE category (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_name VARCHAR(128) NOT NULL UNIQUE,
  code VARCHAR(64) NOT NULL UNIQUE
);

CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  lot_id INT,
  rate_id INT,
  email VARCHAR(128) NOT NULL UNIQUE,
  pwd CHAR(64) NOT NULL,
  username VARCHAR(128) NOT NULL,
  contacts TEXT,
  date_reg TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE lot (
  id INT AUTO_INCREMENT PRIMARY KEY,
  author_id INT,
  winner_id INT,
  category_id INT,
  lot_name VARCHAR(255) NOT NULL,
  lot_desc VARCHAR(255),
  img_url VARCHAR(255),
  lot_price DECIMAL(10,2) NOT NULL,
  rate_step TINYINT NOT NULL,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_exp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (author_id) REFERENCES user(id),
  FOREIGN KEY (winner_id) REFERENCES user(id),
  FOREIGN KEY (category_id) REFERENCES category(id)
);

CREATE TABLE rate (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  lot_id INT,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  rate_price DECIMAL NOT NULL,
  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (lot_id) REFERENCES lot(id)
);

ALTER TABLE user ADD FOREIGN KEY (lot_id) REFERENCES lot(id);
ALTER TABLE user ADD FOREIGN KEY (rate_id) REFERENCES rate(id);

CREATE INDEX user_email ON user(email);
CREATE INDEX lot_name ON lot(lot_name);
CREATE INDEX lot_price ON lot(lot_price);
CREATE INDEX lot_dt_add ON lot(date_add);
CREATE INDEX lot_dt_exp ON lot(date_exp);
