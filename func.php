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
        'mm' => intdiv($time_interval, 3600) % 60
    ];
}
