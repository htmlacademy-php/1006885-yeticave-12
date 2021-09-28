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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $lot = $_POST;
        $errors = validate($lot);
        $file_data = validate_file(count($errors));
        $errors = array_merge($errors, $file_data['errors']);
        $file_url = $file_data['url'];

        if (count($errors)) {
            $page_content = include_template('add.php', [
                'lots_categories' => $lots_categories,
                'errors' => $errors
            ]);
        } else {
            $lot['lot-date'] = $lot['lot-date'] . ' ' . date("H:i:s");
            $sql = 'INSERT INTO lot
                        (author_id, lot_name, category_id, lot_desc, lot_price, rate_step, date_exp, img_url)
                        VALUES (1, ?, (SELECT id FROM category WHERE category_name = ?), ?, ?, ?, ?, "' . $file_url . '")';
            $stmt = db_get_prepare_stmt($link, $sql, $lot);
            $res = mysqli_stmt_execute($stmt);

            if ($res) {
                $lot_id = mysqli_insert_id($link);

                header("Location: lot.php?lot_id=" . $lot_id);
            }
        }
    } else {
        $page_content = include_template('add.php', [
            'lots_categories' => $lots_categories
        ]);
    }

    $layout_content = include_template('layout.php', [
        'title' => 'Добавление лота',
        'is_auth' => $is_auth,
        'user_name' => $user_name,
        'lots_categories' => $lots_categories,
        'content' => $page_content,
    ]);
}

print($layout_content);