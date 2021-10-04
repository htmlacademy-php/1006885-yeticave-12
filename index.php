<?php
require_once('init.php');

if ($link) {
    $sql_lot = 'SELECT l.id, l.lot_name, l.lot_price, l.img_url, l.date_add, l.date_exp, c.category_name
                FROM lot l
                JOIN category c
                ON l.category_id = c.id
                WHERE l.date_exp > ?
                ORDER BY l.date_add DESC';
    $stmt = mysqli_prepare($link, $sql_lot);
    mysqli_stmt_bind_param($stmt, 's', $today);
    mysqli_stmt_execute($stmt);
    $result_lots = mysqli_stmt_get_result($stmt);
    $lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);

    $main_content = include_template('main.php', [
        'lots_categories' => $lots_categories,
        'lots' => $lots]);

    $layout_content = include_template('layout.php', [
        'title' => $config['sitename'],
        'lots_categories' => $lots_categories,
        'content' => $main_content,
    ]);

    print($layout_content);
}

