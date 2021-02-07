<?php

/**
 * Créer un token dans la base de données qui servira à la confirmation de l'inscription
 *
 * @param $length
 * @return false|string
 */
function token($length)
{
    $alphabet = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
}

/**
 * Vérifie si un cookie a été généré et si l'utilisateur n'a pas une session "auth" d'active.
 * Si c'est le cas, connecte l'utilisateur.
 * Sinon le renvoi sur le page d'accueil.
 *
 */
function reconnectCookie() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_COOKIE['remember']) && !isset($_SESSION['auth'])) {
        require_once '../pdo/pdo.php';
        if (isset($pdo)) {
            global $pdo;
        }
        $rememberToken = $_COOKIE['remember'];
        $parts = explode('==', $rememberToken);
        $userId = $parts[0];

        $sql = 'SELECT * FROM netflix_like.users WHERE id = :id';
        $req = $pdo->prepare($sql);
        $req->execute([":id" => $userId]);
        $user = $req->fetch();

        if ($user) {
            $expected = $userId . '==' . $user->cookie_token . sha1($userId . 'flixnet');
            if ($expected === $rememberToken) {
                session_start();
                $_SESSION['auth'] = $user;
                setcookie('remember', $rememberToken, time() + 60 * 60 * 24 * 7);
            }
        } else {
            setcookie('remember', null, -1);
        }
    } else {
        setcookie('remember', null, -1);
    }
}