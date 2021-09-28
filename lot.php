<?php
require_once('db.php');
require_once('func.php');

$is_auth = rand(0, 1);
$user_name = 'Андрей Беляев';

$link = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);
mysqli_set_charset($link, 'utf8');

if (!$link) {
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error]);
    $layout_content = include_template('layout.php', [
        'content' => $page_content
    ]);
} else {
    $sql_category = 'SELECT code, category_name FROM category';
    $result_category = mysqli_query($link, $sql_category);
    $lots_categories = mysqli_fetch_all($result_category, MYSQLI_ASSOC);

    $lot_id = filter_input(INPUT_GET, 'lot_id');

    if ($lot_id) {
        $sql_lot = 'SELECT l.id, l.lot_name, l.lot_price, l.rate_step, l.img_url, l.date_exp, l.lot_desc, c.category_name
                    FROM lot l
                    JOIN category c ON l.category_id = c.id
                    WHERE l.id=' . $lot_id;
        $result_lot = mysqli_query($link, $sql_lot);
        $lot = mysqli_fetch_array($result_lot, MYSQLI_ASSOC);

        if ($lot) {
            $lot_content = include_template('lot.php', [
                'lots_categories' => $lots_categories,
                'lot' => $lot
            ]);
        } else {
            http_response_code(404);
            $lot_content = include_template('404.php', [
                'lots_categories' => $lots_categories
            ]);
        }
    } else {
        $lot_content = include_template('error.php', ['error' => 'Отсутствует идентификатор товара в запросе']);
    }

    $layout_content = include_template('layout.php', [
        'title' => $lot['lot_name'],
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'lots_categories' => $lots_categories,
        'content' => $lot_content
    ]);
}

print($layout_content);
