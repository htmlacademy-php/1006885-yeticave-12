<?php
require_once('vendor/autoload.php');
require_once('init.php');


if ($link) {
    $transport = (new Swift_SmtpTransport('smtp.mailtrap.io', 2525))
        ->setUsername('60dfeee404daec')
        ->setPassword('16e91192a36a93');

    $sql_query = 'SELECT id, lot_name
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

        if (!$bet_data) {
            continue;
        }

        $sql_query = 'UPDATE lot
                SET winner_id = ?,
                    winner_bet_id = ?
                WHERE id = ?';
        $data = [$bet_data['user_id'], $bet_data['id'], $lot['id']];
        $stmt = db_get_prepare_stmt($link, $sql_query, $data);
        $res_2 = mysqli_stmt_execute($stmt);

        $sql_query = 'SELECT email, username
                FROM user
                WHERE id = ?';
        $stmt = mysqli_prepare($link, $sql_query);
        mysqli_stmt_bind_param($stmt, 'i', $bet_data['user_id']);
        mysqli_stmt_execute($stmt);
        $res_3 = mysqli_stmt_get_result($stmt);
        $user_data = mysqli_fetch_array($res_3, MYSQLI_ASSOC);

        $mail_body = include_template('email.php', [
            'lot' => $lot,
            'user_name' => $user_data['username']
        ]);

        $message = (new Swift_Message('Ваша ставка победила'))
            ->setFrom(['keks@phpdemo.ru' => 'Кекс'])
            ->setTo([$user_data['email'] => $user_data['username']])
            ->setBody($mail_body, 'text/html');

        $mailer = (new Swift_Mailer($transport))
            ->send($message);
    }
}
