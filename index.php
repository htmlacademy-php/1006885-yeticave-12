<?php
require_once('date_time.php');
require_once('config.php');
require_once('func.php');
require_once('data.php');

$main_content = include_template('main.php', [
    'product_categories' => $product_categories,
    'products' => $products]);
$layout_content = include_template('layout.php', [
    'title' => $config['sitename'],
    'isAuth' => $is_auth,
    'user_name' => $user_name,
    'product_categories' => $product_categories,
    'main_content' => $main_content,
    ]);

print($layout_content);
