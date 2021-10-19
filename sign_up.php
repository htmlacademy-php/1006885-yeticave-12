<?php
require_once('init.php');

if ($link) {
    if (isset($_SESSION['user'])) {
        http_response_code(403);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $form = $_POST;
        $errors = validate($form);

        if (count($errors)) {
            $page_content = include_template('sign_up.php', [
                'nav' => $nav_content,
                'errors' => $errors
            ]);
        } else {
            $email = mysqli_real_escape_string($link, $form['email']);
            $sql = 'SELECT id FROM user WHERE email = ?';
            $stmt = mysqli_prepare($link, $sql);
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($res) > 0) {
                $errors['form'] = 'Пользователь с этим email уже зарегистрирован';
                $page_content = include_template('sign_up.php', [
                    'nav' => $nav_content,
                    'errors' => $errors
                ]);
            }
            else {
                $password = password_hash($form['password'], PASSWORD_DEFAULT);

                $sql = 'INSERT INTO user (email, pwd, username, contacts) VALUES (?, ?, ?, ?)';
                $stmt = db_get_prepare_stmt($link, $sql, [$form['email'], $password, $form['name'], $form['message_sign_up']]);
                $res = mysqli_stmt_execute($stmt);
            }

            if ($res && empty($errors)) {
                header('Location: /login.php');
                exit();
            }
        }
    } else {
        $page_content = include_template('sign_up.php', [
            'nav' => $nav_content,
            ]);
    }

    $layout_content = include_template('layout.php', [
        'title' => 'Регистрация',
        'lots_categories' => $lots_categories,
        'content' => $page_content,
        'nav' => $nav_content,
        'lot_search' => ''
    ]);

    print($layout_content);
}
