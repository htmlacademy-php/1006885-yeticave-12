<?php
require_once('init.php');
require_once('func.php');

if ($link) {
    if (!isset($_SESSION['user'])) {
        http_response_code(403);
        exit();
    }
    $user_id = $_SESSION['user']['id'];
    $sql_query = 'SELECT
                        l.lot_name, b.user_id, b.lot_id, b.date_add, b.bet_price, l.category_id, c.category_name, l.img_url, l.date_exp, l.winner_id, u.contacts
                    FROM bet b
                    JOIN lot l
                    ON l.id = b.lot_id
                    JOIN category c
                    ON c.id = l.category_id
                    JOIN user u
                    ON u.id = l.owner_id
                    WHERE b.user_id = ?
                    ORDER BY b.date_add DESC';
    $data = [$user_id];
    $stmt = db_get_prepare_stmt($link, $sql_query, $data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $bets = mysqli_fetch_all($res, MYSQLI_ASSOC);

    $page_content = include_template('my_bets.php', [
        'nav' => $nav_content,
        'bets' => $bets,
        'now' => $now,
        'user_id' => $user_id
    ]);


    $layout_content = include_template('layout.php', [
        'title' => 'Мои ставки',
        'lots_categories' => $lots_categories,
        'nav' => $nav_content,
        'content' => $page_content,
        'lot_search' => ''
    ]);

    print($layout_content);
}
