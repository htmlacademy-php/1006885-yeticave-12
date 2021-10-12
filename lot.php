<?php
require_once('init.php');
require_once('func.php');

if ($link) {
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
                'nav' => $nav_content,
                'lot' => $lot
            ]);
        } else {
            http_response_code(404);
            $lot_content = include_template('404.php', [
                'nav' => $nav_content
            ]);
        }
    } else {
        $lot_content = include_template('error.php', [
            'nav' => $nav_content,
            'error' => 'Отсутствует идентификатор товара в запросе'
        ]);
    }

    $layout_content = include_template('layout.php', [
        'title' => $lot['lot_name'],
        'lots_categories' => $lots_categories,
        'content' => $lot_content
    ]);

    print($layout_content);
}
