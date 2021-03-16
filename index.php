<?php
require_once('config.php');
require_once('db.php');
require_once('func.php');
// require_once('data.php');

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, 'utf8');

$lots_categories = [];
$lots = [];
$today = date('Y-m-d');

if (!$link) {
    $error = mysqli_connect_error();
    $content = include_template('error.php', ['error' => $error]);
} else {
    $sql_category = 'SELECT code, category_name FROM category';
    $result_category = mysqli_query($link, $sql_category);

    $sql_lot = 'SELECT l.lot_name, l.lot_price, l.img_url, l.date_exp, c.category_name
                        FROM lot l
                        JOIN category c
                        ON l.category_id = c.id
                        WHERE l.date_exp >= "'.$today.'"';
    $result_lot = mysqli_query($link, $sql_lot);

    if ($result_category && $result_lot) {
        $lots_categories = mysqli_fetch_all($result_category, MYSQLI_ASSOC);
        $lots = mysqli_fetch_all($result_lot, MYSQLI_ASSOC);

        $main_content = include_template('main.php', [
            'lots_categories' => $lots_categories,
            'lots' => $lots]);

    } else {
        $error = mysqli_error($link);
        $main_content = include_template('error.php', ['error' => $error]);
    }
}

$layout_content = include_template('layout.php', [
    'title' => $config['sitename'],
    'isAuth' => $is_auth,
    'user_name' => $user_name,
    'lots_categories' => $lots_categories,
    'main_content' => $main_content,
    ]);

print($layout_content);
