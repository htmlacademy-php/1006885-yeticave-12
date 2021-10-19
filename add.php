<?php
require_once('init.php');
require_once('func.php');

if ($link) {
    if (!isset($_SESSION['user'])) {
        http_response_code(403);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $lot_data = $_POST;
        $errors = validate($lot_data);
        $file_data = validate_file(count($errors));
        $errors = array_merge($errors, $file_data['errors']);
        $file_url = $file_data['url'];

        if (count($errors)) {
            $page_content = include_template('add.php', [
                'nav' => $nav_content,
                'lots_categories' => $lots_categories,
                'errors' => $errors
            ]);
        } else {
            $user_id = $_SESSION['user']['id'];
            array_unshift($lot_data, $user_id);
            array_push($lot_data, $file_url);
            $lot_data['lot-date'] = $lot_data['lot-date'] . ' ' . date('H:i:s');
            $sql_query = 'INSERT INTO lot
                        (owner_id, lot_name, category_id, lot_desc, lot_price, bet_step, date_exp, img_url)
                        VALUES (?, ?, (SELECT id FROM category WHERE category_name = ?), ?, ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($link, $sql_query, $lot_data);
            $res = mysqli_stmt_execute($stmt);

            if ($res) {
                $lot_id = mysqli_insert_id($link);

                header('Location: lot.php?lot_id=' . $lot_id);
            }
        }
    } else {
        $page_content = include_template('add.php', [
            'nav' => $nav_content,
            'lots_categories' => $lots_categories
        ]);
    }

    $layout_content = include_template('layout.php', [
        'title' => 'Добавление лота',
        'lots_categories' => $lots_categories,
        'content' => $page_content,
    ]);

    print($layout_content);
}
