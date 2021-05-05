<?php
require_once('db.php');
require_once('func.php');

$link = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);
mysqli_set_charset($link, 'utf8');

if (!$link) {
    $error = mysqli_connect_error();
    $main_content = include_template('error.php', ['error' => $error]);
    $layout_content = include_template('layout.php', [
        'main_content' => $main_content
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
            $layout_content = include_template('lot.php', [
                'lots_categories' => $lots_categories,
                'lot' => $lot
            ]);
        } else {
            http_response_code(404);
            $layout_content = include_template('404.php', []);
        }
    }
}

print($layout_content);
