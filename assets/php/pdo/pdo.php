<?php

$dsn = "mysql:host=localhost;dbname=netflix_like;charset=utf8";
$user = 'root';
$password = '';
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;",
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
);

try {
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (Throwable $th) {
    throw $th;
}
