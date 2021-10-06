<?php
require_once('config.php');
require_once('db.php');
require_once('func.php');

session_start();

$today = date('Y-m-d');

$link = connect_db($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

if ($link) {
    $sql_category = 'SELECT code, category_name FROM category';
    $result_category = mysqli_query($link, $sql_category);
    $lots_categories = mysqli_fetch_all($result_category, MYSQLI_ASSOC);
    $nav_content = include_template('nav.php', [
        'lots_categories' => $lots_categories
    ]);
}
