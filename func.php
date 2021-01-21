<?php
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

function filter_xss($str) {
    return htmlspecialchars($str);
};

function get_time_interval($date) {
    $time_interval = strtotime($date) - time();

    return [
        'hh' => intdiv($time_interval, 3600),
        'mm' => 60 - date('i') - 1 // количество минут до конца часа (т.к. текущая минута уже идет, надо отнять еще единицу)
                                   // если я правильно понял условие поставленной задачи) нам же надо считать до 0 часов 0 минут даты expired
                                   // следовательно, количество часов будет сокращаться, а количество минут при этом будет меняться от 59 до 0
    ];
}
