<?php
require_once('init.php');
require_once('func.php');

if ($link) {
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!isset($_SESSION['user'])) {
            http_response_code(403);
            exit();
        }

        $lot_id = filter_input(INPUT_POST, 'lot_id', FILTER_SANITIZE_NUMBER_INT);
        $bet_data = $_POST;
        $errors = validate($bet_data);

        if (!count($errors)) {
            $user_id = $_SESSION['user']['id'];
            mysqli_begin_transaction($link);
            $sql = 'INSERT INTO rate
                        (user_id, lot_id, rate_price)
                        VALUES (?, ?, ?)';
            $data = [$user_id, $lot_id, $bet_data['cost']];
            $stmt = db_get_prepare_stmt($link, $sql, $data);
            $res_1 = mysqli_stmt_execute($stmt);
            $sql = 'UPDATE lot
                        SET lot_price = ?
                        WHERE id = ?';
            $data = [$bet_data['cost'], $lot_id];
            $stmt = db_get_prepare_stmt($link, $sql, $data);
            $res_2 = mysqli_stmt_execute($stmt);

            if ($res_1 && $res_2) {
                mysqli_commit($link);

                header('Location: lot.php?lot_id=' . $lot_id);
                exit();
            } else {
                mysqli_rollback($link);
            }
        }
    } else {
        $lot_id = filter_input(INPUT_GET, 'lot_id', FILTER_SANITIZE_NUMBER_INT);
    }

    if ($lot_id) {
        $sql_query = 'SELECT l.id, l.lot_name, l.lot_price, l.rate_step, l.img_url, l.date_exp, l.lot_desc, c.category_name
                        FROM lot l
                        JOIN category c ON l.category_id = c.id
                        WHERE l.id=' . $lot_id;
        $res = mysqli_query($link, $sql_query);
        $lot = mysqli_fetch_array($res, MYSQLI_ASSOC);

        $bets = [];
        if (isset($_SESSION['user'])) {
            $sql_query = 'SELECT u.username, r.date_add, r.rate_price
                            FROM rate r
                            JOIN user u
                            ON u.id = r.user_id
                            WHERE r.lot_id =' . $lot_id;
            $res = mysqli_query($link, $sql_query);
            $bets = mysqli_fetch_all($res, MYSQLI_ASSOC);
        }

        if ($lot) {
            $page_content = include_template('lot.php', [
                'nav' => $nav_content,
                'lot' => $lot,
                'bets' => $bets,
                'bets_count' => count($bets),
                'errors' => $errors
            ]);
        } else {
            http_response_code(404);
            $page_content = include_template('404.php', [
                'nav' => $nav_content
            ]);
        }
    } else {
        $page_content = include_template('error.php', [
            'nav' => $nav_content,
            'error' => 'Отсутствует идентификатор товара в запросе'
        ]);
    }

    $layout_content = include_template('layout.php', [
        'title' => $lot['lot_name'],
        'lots_categories' => $lots_categories,
        'content' => $page_content
    ]);

    print($layout_content);
}
