<?php
require_once('db.php');
require_once('func.php');

$is_auth = 0;
//$user_name = 'Андрей Беляев';

$link = connect_db($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

if ($link) {
    $sql_category = 'SELECT code, category_name FROM category';
    $result_category = mysqli_query($link, $sql_category);
    $lots_categories = mysqli_fetch_all($result_category, MYSQLI_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $form = $_POST;
        $errors = validate($form);

        if (count($errors)) {
            $page_content = include_template('sign_up.php', [
                'lots_categories' => $lots_categories,
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
                    'lots_categories' => $lots_categories,
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
                header('Location: /login.html');
                exit();
            }
        }
    } else {
        $page_content = include_template('sign_up.php', [
            'lots_categories' => $lots_categories,
            ]);
    }

    $layout_content = include_template('layout.php', [
        'title' => 'Добавление лота',
        'is_auth' => $is_auth,
//        'user_name' => $user_name,
        'lots_categories' => $lots_categories,
        'content' => $page_content,
    ]);

    print($layout_content);
}
