<?php
require_once('init.php');
require_once('func.php');

if ($link) {
    $lot_search = filter_input(INPUT_GET, 'search');

    if (!$lot_search) {
        $page_content = include_template('error.php', [
            'nav' => $nav_content,
            'error' => 'Вы ввели пустой запрос'
        ]);
    } else {
        $current_page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT) ?? 1;
        $lots_per_page = 9;

        $sql_query = 'SELECT COUNT(*) as total
            FROM lot
            WHERE date_exp > ?
            AND MATCH(lot_name, lot_desc) AGAINST(?)';
        $stmt = mysqli_prepare($link, $sql_query);
        mysqli_stmt_bind_param($stmt, 'ss', $now, $lot_search);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $lots_total = mysqli_fetch_assoc($res)['total'];

        $pages_total = ceil($lots_total / $lots_per_page);
        $offset = ($current_page - 1) * $lots_per_page;
        $pages = range(1, $pages_total);

        $sql_query = 'SELECT l.id, lot_name, lot_desc, lot_price, l.img_url, l.date_add, l.date_exp, c.category_name
            FROM lot l
            JOIN category c
            ON l.category_id = c.id
            WHERE l.date_exp > ?
            AND MATCH(lot_name, lot_desc) AGAINST(?)
            ORDER BY l.date_add DESC
            LIMIT ? OFFSET ?';

        $stmt = mysqli_prepare($link, $sql_query);
        mysqli_stmt_bind_param($stmt, 'ssii', $now, $lot_search, $lots_per_page, $offset);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $lots = mysqli_fetch_all($res, MYSQLI_ASSOC);

        $page_content = include_template('search.php', [
            'nav' => $nav_content,
            'lot_search' => $lot_search,
            'pages_total' => $pages_total,
            'current_page' => $current_page,
            'pages' => $pages,
            'lots' => $lots
        ]);
    }

    $layout_content = include_template('layout.php', [
        'title' => 'Результаты поиска',
        'lots_categories' => $lots_categories,
        'lot_search' => $lot_search,
        'content' => $page_content
    ]);

    print($layout_content);
}
