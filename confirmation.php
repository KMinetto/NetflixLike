<?php

require_once "assets/php/require/header.php";

$userId = $_GET['id'];
$token = $_GET['token'];

$sql = "SELECT * FROM netflix_like.users WHERE id = :id";
$req = $pdo->prepare($sql);
$req->execute([':id' => $userId]);
$user = $req->fetch();

if ($user) {
    $userToken = $user->confirmation_token;
    if ($userToken === $token) {
        $sql = 'UPDATE netflix_like.users SET confirmation_token = NULL, confirmed_at = NOW() WHERE id= :id';
        $req = $pdo->prepare($sql);
        $req->execute([":id" => $user->id]);

        $_SESSION['flash']['success'] = 'Votre inscription a bien été confirmée ! Connectez-vous !';
        header('Location: connection.php');
        exit();
    }

    if ($userToken === NULL) {
        $_SESSION['flash']['errors'] = 'Votre inscription a déjà été confirmé. Connectez-vous !';
        header('Location: connection.php');
        exit();
    }
}

$_SESSION['flash']['errors'] = 'Nous ne pouvons pas confirmer votre inscription';
header('Location: inscription.php');

require_once "assets/php/require/footer.php";