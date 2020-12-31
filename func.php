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
