<?php

require_once "assets/php/pdo/pdo.php";
require_once "assets/php/functions/functions.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="assets/img/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Netflix Like | <?= $title ?></title>
</head>
<body>
<div class="container">
    <div class="row">
        <header>
            <nav class="d-flex justify-content-between">
                <div class="col-2">
                    <a id="index" href="index.php"><img src="assets/img/Flixnet_logo.svg" alt="Logo Netflix Like"></a>
                </div>
                <div>
                    <ul class="list-inline">
                        <?php if (!empty($_SESSION['auth'])) : ?>
                            <li class="nav-item"><a href="logout.php" class="nav-link text-white">Se déconnecter</a></li>
                        <?php else : ?>
                            <li class="nav-item list-inline-item"><a class="nav-link text-white active" href="inscription.php">Inscription</a></li>
                            <li class="nav-item list-inline-item"><a class="nav-link text-white" href="connection.php">Connexion</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </nav>
        </header>