DROP DATABASE IF EXISTS yeticave;

CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE category (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_name VARCHAR(255) NOT NULL UNIQUE,
  code VARCHAR(128) NOT NULL UNIQUE
);

CREATE TABLE user (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  pwd CHAR(128) NOT NULL,
  username VARCHAR(255) NOT NULL,
  contacts TEXT,
  date_reg TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE lot (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  owner_id INT UNSIGNED NOT NULL,
  winner_id INT UNSIGNED DEFAULT NULL,
  winner_bet_id INT UNSIGNED DEFAULT NULL,
  category_id INT UNSIGNED,
  lot_name VARCHAR(255) NOT NULL,
  lot_desc TEXT,
  img_url VARCHAR(255),
  lot_price DECIMAL(10,2) NOT NULL,
  bet_step SMALLINT NOT NULL,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_exp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE bet (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  lot_id INT UNSIGNED NOT NULL,
  bet_price DECIMAL NOT NULL,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

