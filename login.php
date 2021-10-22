<?php
require_once('init.php');

if ($link) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $form_data = $_POST;
        $errors = validate($form_data);

        if (count($errors)) {
            $page_content = include_template('login.php', [
                'nav' => $nav_content,
                'errors' => $errors
            ]);
        } else {
            $email = mysqli_real_escape_string($link, $form_data['email']);
            $sql_query = 'SELECT * FROM user WHERE email = ?';
            $stmt = mysqli_prepare($link, $sql_query);
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            $res = mysqli_stmt_get_result($stmt);

            $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

            if ($user) {
                if (password_verify($form_data['password'], $user['pwd'])) {
                    $_SESSION['user'] = $user;
                }
                else {
                    $errors['password'] = 'Неверный пароль';
                }
            } else {
                $errors['email'] = 'Такой пользователь не найден';
            }

            if ($user && empty($errors)) {
                header('Location: /index.php');
                exit();
            } else {
                $page_content = include_template('login.php', [
                    'nav' => $nav_content,
                    'errors' => $errors
                ]);
            }
        }
    } else {
        $page_content = include_template('login.php', [
            'nav' => $nav_content,
        ]);

        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit();
        }
    }

    $layout_content = include_template('layout.php', [
        'title' => 'Регистрация',
        'lots_categories' => $lots_categories,
        'nav' => $nav_content,
        'content' => $page_content,
        'lot_search' => ''
    ]);

    print($layout_content);
}
