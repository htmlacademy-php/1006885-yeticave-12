<?php
require_once('config.php');
require_once('db.php');
require_once('func.php');

session_start();

date_default_timezone_set('Europe/Moscow');
$now = date('Y-m-d H:i:s');

$link = connect_db($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

if ($link) {
    $sql_query = 'SELECT code, category_name FROM category';
    $res = mysqli_query($link, $sql_query);
    $lots_categories = mysqli_fetch_all($res, MYSQLI_ASSOC);
    $nav_content = include_template('nav.php', [
        'lots_categories' => $lots_categories
    ]);
}
