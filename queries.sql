INSERT INTO category (category_name, code)
VALUES  ('Доски и лыжи', 'boards'),
        ('Крепления', 'attachment'),
        ('Ботинки', 'boots'),
        ('Одежда', 'clothing'),
        ('Инструменты', 'tools'),
        ('Разное', 'other');

INSERT INTO user (username, email, pwd, contacts)
VALUES ('Vasiliy', 'vas@mail.ru', 'secret', 'Moscow');

INSERT INTO user (username, email, pwd, contacts)
VALUES ('Petr', 'petr@mail.ru', 'supersecret', 'Novgorod');

INSERT INTO lot (lot_name, lot_price, img_url, date_exp, rate_step, author_id, category_id)
VALUES ('2014 Rossignol District Snowboard', 10999, 'img/lot-1.jpg', '2021-03-14', 20, 1, 1);

INSERT INTO lot (lot_name, lot_price, img_url, date_exp, rate_step, author_id, category_id)
VALUES ('DC Ply Mens 2016/2017 Snowboard', 159999, 'img/lot-2.jpg', '2021-03-17', 1000, 1, 1);

INSERT INTO lot (lot_name, lot_price, img_url, date_exp, rate_step, author_id, category_id)
VALUES ('Крепления Union Contact Pro 2015 года размер L/XL', 8000, 'img/lot-3.jpg', '2021-04-25', 20, 2, 2);

INSERT INTO lot (lot_name, lot_price, img_url, date_exp, rate_step, author_id, category_id)
VALUES ('Ботинки для сноуборда DC Mutiny Charocal', 10999, 'img/lot-4.jpg', '2021-04-03', 50, 1, 3);

INSERT INTO lot (lot_name, lot_price, img_url, date_exp, rate_step, author_id, category_id)
VALUES ('Куртка для сноуборда DC Mutiny Charocal', 7500, 'img/lot-5.jpg', '2021-03-18', 20, 2, 4);

INSERT INTO lot (lot_name, lot_price, img_url, date_exp, rate_step, author_id, category_id)
VALUES ('Маска Oakley Canopy', 5400, 'img/lot-6.jpg', '2021-05-10', 10, 2, 6);

INSERT INTO rate (user_id, lot_id, rate_price)
VALUES (2, 1, 11019);

INSERT INTO rate (user_id, lot_id, rate_price)
VALUES (1, 1, 11039);

INSERT INTO rate (user_id, lot_id, rate_price)
VALUES (2, 1, 11059);

INSERT INTO rate (user_id, lot_id, rate_price)
VALUES (1, 5, 7520);


SELECT * FROM category;

SELECT l.id, l.lot_name, l.lot_price, l.img_url, r.rate_price, c.category_name FROM lot l
JOIN category c
ON l.category_id = c.id
JOIN rate r
ON r.lot_id = l.id
WHERE l.date_exp >= '2021-02-16';

SELECT l.id, l.lot_name, c.category_name
FROM lot l
JOIN category c
ON l.id = c.id
WHERE l.id = 3;

UPDATE lot
SET lot_name = 'Маска Oakley Canopy NEW'
WHERE id = 6;

SELECT r.rate_price, r.date_add, l.lot_name
FROM rate r
JOIN lot l
ON r.lot_id = l.id
WHERE r.lot_id = 1
ORDER BY r.date_add;
