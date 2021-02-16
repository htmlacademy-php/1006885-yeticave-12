INSERT INTO category SET category_name = 'Доски и лыжи', code = 'boards';
INSERT INTO category SET category_name = 'Крепления', code = 'attachment';
INSERT INTO category SET category_name = 'Ботинки', code = 'boots';
INSERT INTO category SET category_name = 'Одежда', code = 'clothing';
INSERT INTO category SET category_name = 'Инструменты', code = 'tools';
INSERT INTO category SET category_name = 'Разное', code = 'other';

INSERT INTO user SET username = 'Vasiliy', email = 'vas@mail.ru', pwd = 'secret', contacts = 'Moscow';
INSERT INTO user SET username = 'Petr', email = 'petr@mail.ru', pwd = 'supersecret', contacts = 'Novgorod';

INSERT INTO lot SET lot_name = '2014 Rossignol District Snowboard', lot_price = 10999, img_url = 'img/lot-1.jpg', date_exp = '2021-03-14', rate_step = 20, author_id = 1, category_id = 1;
INSERT INTO lot SET lot_name = 'DC Ply Mens 2016/2017 Snowboard', lot_price = 159999, img_url = 'img/lot-2.jpg', date_exp = '2021-03-17', rate_step = 1000, author_id = 1, category_id = 1;
INSERT INTO lot SET lot_name = 'Крепления Union Contact Pro 2015 года размер L/XL', lot_price = 8000, img_url = 'img/lot-3.jpg', date_exp = '2021-04-25', rate_step = 20, author_id = 2, category_id = 2;
INSERT INTO lot SET lot_name = 'Ботинки для сноуборда DC Mutiny Charocal', lot_price = 10999, img_url = 'img/lot-4.jpg', date_exp = '2021-04-03', rate_step = 50, author_id = 1, category_id = 3;
INSERT INTO lot SET lot_name = 'Куртка для сноуборда DC Mutiny Charocal', lot_price = 7500, img_url = 'img/lot-5.jpg', date_exp = '2021-03-18', rate_step = 20, author_id = 2, category_id = 4;
INSERT INTO lot SET lot_name = 'Маска Oakley Canopy', lot_price = 5400, img_url = 'img/lot-6.jpg', date_exp = '2021-05-10', rate_step = 10, author_id = 2, category_id = 6;

INSERT INTO rate SET user_id = 2, lot_id = 1, rate_price = 11019;
INSERT INTO rate SET user_id = 1, lot_id = 1, rate_price = 11039;
INSERT INTO rate SET user_id = 2, lot_id = 1, rate_price = 11059;
INSERT INTO rate SET user_id = 1, lot_id = 5, rate_price = 7520;


SELECT * FROM category;

SELECT l.id, lot_name, lot_price, img_url, rate_price, category_name FROM lot l
JOIN category c
ON category_id = c.id
JOIN rate r
ON r.lot_id = l.id
WHERE date_exp >= '2021-02-16';

SELECT lot.id, lot_name, category_name
FROM lot
JOIN category c
ON lot.id = c.id
WHERE lot.id = 3;

UPDATE lot SET lot_name = 'Маска Oakley Canopy NEW' WHERE id = 6;

SELECT rate_price, r.date_add, lot_name
FROM rate r
JOIN lot
ON r.lot_id = lot.id
WHERE lot_id = 1
ORDER BY r.date_add;
