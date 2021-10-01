<?php
function connect_db($host, $user, $pwd, $name) {
    $link = mysqli_connect($host, $user, $pwd, $name);
    mysqli_set_charset($link, 'utf8');

    if (!$link) {
        $error = mysqli_connect_error();
        $page_content = include_template('error.php', ['error' => $error]);
        $layout_content = include_template('layout.php', [
            'content' => $page_content,
            'lots_categories' => []
        ]);
        print($layout_content);
        return false;
    } else {
        return $link;
    }
}

function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

function format_price(float $price): string
{
    return ceil($price) < 1000 ? ceil($price) . ' ₽' : number_format(ceil($price), 0, ',', ' ') . ' ₽';
}

function filter_user_data($data) : string {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
};

function get_time_interval($date) : array {
    $time_interval = strtotime($date) - time();

    return [
        'hh' => intdiv($time_interval, 3600),
        'mm' => 59 - date('i')
    ];
}

function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

function get_post_value($name) : string {
    return filter_user_data($_POST[$name]) ?? '';
}

function validate($data) : array {
    $errors = [];
    foreach ($data as $key => $value) {
        switch ($key) {
            case 'lot-name':
                $errors[$key] = empty($value) ? 'Введите наименование лота' : null;
                break;
            case 'category':
                $errors[$key] = $value === 'Выберите категорию' ? 'Выберите категорию' : null;
                break;
            case 'message':
                $errors[$key] = empty($value) ? 'Напишите описание лота' : null;
                break;
            case 'lot-rate':
                if (empty($value)) {
                    $errors[$key] = 'Введите начальную цену';
                } elseif (!is_numeric($value) || $value <= 0) {
                    $errors[$key] = 'Введите положительное число';
                }
                break;
            case 'lot-step':
                if (empty($value)) {
                    $errors[$key] = 'Введите шаг ставки';
                } elseif (!ctype_digit($value) || $value <= 0) {
                    $errors[$key] = 'Введите целое положит. число';
                }
                break;
            case 'lot-date':
                if (empty($value)) {
                    $errors[$key] = 'Введите дату завершения торгов';
                } elseif (!is_date_valid($value)) {
                    $errors[$key] = 'Неверный формат даты';
                } elseif ($value <= date('Y-m-d')) {
                    $errors[$key] = 'Дата должна быть больше текущей даты';
                }
                break;
            case 'email':
                $errors[$key] = empty($value) ? 'Введите e-mail' : null;
                break;
            case 'password':
                $errors[$key] = empty($value) ? 'Введите пароль' : null;
                break;
            case 'name':
                $errors[$key] = empty($value) ? 'Введите имя' : null;
                break;
            case 'message_sign_up':
                $errors[$key] = empty($value) ? 'Напишите как с вами связаться' : null;
                break;
        }
    }
    return array_filter($errors);
}

function validate_file($is_errors) : array {
    $errors = [];
    $file_url = null;

    $file_mime_types = [
        'image/png',
        'image/jpg',
        'image/jpeg'
    ];

    if (!empty($_FILES['lot-img']['name'])) {
        $tmp_name = $_FILES['lot-img']['tmp_name'];
        $file_name = $_FILES['lot-img']['name'];
        $file_path = __DIR__ . '/uploads/';
        $file_url = '/uploads/' . $file_name;

        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($file_info, $tmp_name);

        if (!in_array($file_type, $file_mime_types)) {
            $errors['file'] = 'Загрузите картинку в формате jpg/jpeg/png';
        } elseif (!$is_errors) {
            move_uploaded_file($_FILES['lot-img']['tmp_name'], $file_path . $file_name);
        }
    } else {
        $errors['file'] = 'Вы не загрузили файл';
    }

    return [
      'errors' => $errors,
      'url' => $file_url
    ];
}
