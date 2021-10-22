<?php
function connect_db(string $host, string $user, string $pwd, string $name) {
    $link = mysqli_connect($host, $user, $pwd, $name);
    mysqli_set_charset($link, 'utf8');

    if (!$link) {
        $error = mysqli_connect_error();
        $page_content = include_template('error.php', ['error' => $error]);
        $layout_content = include_template('layout.php', [
            'content' => $page_content,
            'lot_search' => '',
            'lots_categories' => []
        ]);
        print($layout_content);
        return false;
    } else {
        return $link;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template(string $name, array $data = []): string {
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

function format_price(float $price): string {
    return ceil($price) < 1000 ? ceil($price) . ' ₽' : number_format(ceil($price), 0, ',', ' ') . ' ₽';
}

function filter_user_data($data): string {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

;

function get_time_interval(string $date): array {
    $now = new DateTime('now');
    $date_exp = date_create($date);
    $interval = date_diff($now, $date_exp);

    return [
        'hh' => ($interval->y * 365.25 + $interval->m * 30 + $interval->d) * 24 + $interval->h + $interval->i/60,
        'mm' => $interval->i,
        'ss' => $interval->s
    ];
}

function calculate_time_interval(string $value): string {
    $interval = time() - strtotime($value);
    $date_add = date_create($value);

    if ($interval < 60) {
        return 'менее минуты';
    } else if ($interval < 3600) {
        $mm = floor($interval / 60);
        return $mm . ' ' . get_noun_plural_form($mm, 'минуту', 'минуты', 'минут') . ' назад';
    } else if ($interval < 86400) {
        $hh = floor($interval / 3600);
        return $hh . ' ' . get_noun_plural_form($hh, 'час', 'часа', 'часов') . ' назад';
    } else if ($interval < 86400 * 2) {
        return 'Вчера, в ' . $date_add->format('H:i');
    } else {
        return $date_add->format('d.m.y в H:i');
    }
}

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt(object $link, string $sql, array $data = []) {
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
            } else if (is_string($value)) {
                $type = 's';
            } else if (is_double($value)) {
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

function get_post_value(string $name): string {
    return filter_user_data($_POST[$name]) ?? '';
}

function validate(array $data): array {
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
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$key] = 'Введите корректный email';
                } else {
                    $errors[$key] = empty($value) ? 'Введите e-mail' : null;
                }
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
            case 'cost':
                if (empty($value)) {
                    $errors[$key] = 'Введите Вашу ставку';
                } elseif (!ctype_digit($value) || $value <= 0) {
                    $errors[$key] = 'Введите целое положит. число';
                } elseif ($value < $data['lot_min_bet']) {
                    $errors[$key] = 'Минимальная ставка - ' . $data['lot_min_bet'];
                }
                break;
        }
    }
    return array_filter($errors);
}

function validate_file(bool $is_errors): array {
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

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественного числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string {
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}
