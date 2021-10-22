<?php
require_once('init.php');
require_once('func.php');

if ($link) {
    $category_id = filter_input(INPUT_GET, 'category_id', FILTER_SANITIZE_NUMBER_INT);
    $category_name = '';

    if (!$category_id) {
        $page_content = include_template('error.php', [
            'nav' => $nav_content,
            'error' => 'Отсутствует идентификатор категории в строке запроса'
        ]);
    } else {
        $current_page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT) ?? 1;
        $lots_per_page = 9;

        $sql_query = 'SELECT id, category_name
            FROM category
            WHERE id =' . $category_id;
        $res = mysqli_query($link, $sql_query);
        $category_data = mysqli_fetch_array($res, MYSQLI_ASSOC);

        $sql_query = 'SELECT COUNT(*) as total
            FROM lot
            WHERE category_id = ?
            AND date_exp > ?';
        $stmt = mysqli_prepare($link, $sql_query);
        mysqli_stmt_bind_param($stmt, 'is', $category_id, $now);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $lots_total = mysqli_fetch_assoc($res)['total'];

        $pages_total = ceil($lots_total / $lots_per_page);
        $offset = ($current_page - 1) * $lots_per_page;
        $pages = range(1, $pages_total);

        $sql_query = 'SELECT l.*, c.category_name
            FROM lot l
            JOIN category c
            ON c.id = l.category_id
            WHERE category_id = ?
            AND date_exp > ?
            ORDER BY date_add DESC
            LIMIT ? OFFSET ?';

        $stmt = mysqli_prepare($link, $sql_query);
        mysqli_stmt_bind_param($stmt, 'isii', $category_id, $now, $lots_per_page, $offset);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $lots = mysqli_fetch_all($res, MYSQLI_ASSOC);

        $page_content = include_template('all_lots.php', [
            'nav' => $nav_content,
            'category_data' => $category_data,
            'pages_total' => $pages_total,
            'current_page' => $current_page,
            'pages' => $pages,
            'lots' => $lots
        ]);
    }

    $layout_content = include_template('layout.php', [
        'title' => 'Все лоты в категории ' . $category_name,
        'lots_categories' => $lots_categories,
        'nav' => $nav_content,
        'content' => $page_content,
        'lot_search' => ''
    ]);

    print($layout_content);
}
