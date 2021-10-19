<?php
require_once('init.php');

if ($link) {
    $sql_query = 'SELECT id
                FROM lot
                WHERE date_exp <= ?
                AND winner_id IS NULL';
    $stmt = mysqli_prepare($link, $sql_query);
    mysqli_stmt_bind_param($stmt, 's', $now);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $lots = mysqli_fetch_all($res, MYSQLI_ASSOC);

    if (!$lots) {
        exit();
    }

    foreach ($lots as $lot) {
        $sql_query = 'SELECT id, user_id
                FROM bet
                WHERE lot_id = ?
                ORDER BY date_add DESC
                LIMIT 1';
        $stmt = mysqli_prepare($link, $sql_query);
        mysqli_stmt_bind_param($stmt, 'i', $lot['id']);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $bet_data = mysqli_fetch_array($res, MYSQLI_ASSOC);

        $sql = 'UPDATE lot
                SET winner_id = ?
                WHERE id = ?';
        $data = [$bet_data['user_id'], $lot['id']];
        $stmt = db_get_prepare_stmt($link, $sql, $data);
        $res_2 = mysqli_stmt_execute($stmt);
    }

//    Добавить отправку email победителю
}
