<?php

session_start();
unset($_SESSION['auth']);
$_SESSION['flash']['success'] = 'Vous êtes maintenant déconnecté ! À bientôt !';

header('Location: index.php');

