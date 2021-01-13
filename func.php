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

function get_time_interval($dt_exp) {
    $dt_end = date_create($dt_exp);
    $dt_now = date_create('now');
    $dt_diff = date_diff($dt_now, $dt_end);

    $dd = (int)date_interval_format($dt_diff, '%r%a');

    if ($dd < 0 || date_format($dt_now, 'd-m-Y') === date_format($dt_end, 'd-m-Y')) {
        return false;
    }

    $hh = $dd * 24 + (int)date_interval_format($dt_diff, '%h');
    $hh = str_pad($hh, 2, '0', STR_PAD_LEFT);
    $mm = date_interval_format($dt_diff, '%i');
    $mm = str_pad($mm, 2, '0', STR_PAD_LEFT);

    return [
        'hh' => $hh,
        'mm' => $mm
    ];
}
