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