<?php

$title = "Connexion";
require_once "assets/php/require/header.php";

reconnectCookie();
if (!empty($_SESSION['auth'])) {
    header('location: index.php');
}

if (isset($_POST['submit'])) {
    if (!empty($_POST['pseudo'] && !empty($_POST['password']))) {
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $password = htmlspecialchars($_POST['password']);

        $sql = 'SELECT * FROM netflix_like.users WHERE (pseudo = :pseudo OR email = :pseudo) AND confirmed_at IS NOT NULL';
        $req = $pdo->prepare($sql);
        $req->execute([":pseudo" => $pseudo]);
        $user = $req->fetch();

        if (password_verify($password, $user->password)) {
            $_SESSION['auth'] = $user;
            $_SESSION['flash']['success'] = 'Vous êtes maintenant connecté. Bon visionnage !';

            if (!empty($_POST['checked'])) {
                $cookieToken = token(60);
                $id = $user->id;

                $sql = 'UPDATE netflix_like.users SET cookie_token = :cookie_token WHERE id = :id';
                $req = $pdo->prepare($sql);
                $req->execute([":cookie_token" => $cookieToken]);
                setcookie('remember', $id . '==' . $cookieToken . sha1($id . 'flixnet'), time() . 60 * 60 * 24 * 7);
            }
            header('Location: index.php');
            exit();
        }
        $_SESSION['flash']['errors'] = 'il semblerait que votre identifiant ou votre mot de passe soit incorrect.
            Veuillez réessayer.';
    }
}

?>

<div class="col-12 col-md-4 position-absolute top-50 start-50 translate-middle p-3 px-sm-5 py-sm-5 shadow">
    <div class="container">
        <div class="row">
            <form class="mb-3" action="" method="post">
                <h1 class="text-white">Se connecter</h1>
                <?php if (isset($_SESSION['flash'])) : ?>
                    <?php if (isset($_SESSION['flash']['errors'])) :?>
                        <ul class="alert alert-danger">
                            <?php foreach ($_SESSION['flash'] as $errors) :?>
                                <li class="text-center"><?= $errors ?></li>
                            <?php endforeach; ?>
                            <?php unset($_SESSION['flash']); ?>
                        </ul>
                    <?php elseif (isset($_SESSION['flash']['success'])) :?>
                        <ul class="alert alert-success">
                            <?php foreach ($_SESSION['flash'] as $success) : ?>
                                <li class="text-center"><?= $success ?></li>
                            <?php endforeach; ?>
                            <?php unset($_SESSION['flash']) ?>
                        </ul>
                    <?php endif; ?>
                <?php endif ?>
                <div class="col-12 mb-4"><input type="text" name="pseudo" class="form-control" placeholder="Pseudo/Email"></div>
                <div class="col-12 mb-2"><input type="password" name="password" class="form-control" placeholder="Mot de passe"></div>
                <div class="col-12 d-flex justify-content-end mb-4">
                    <a id="forgot" class="nav-link text-white p-0" href="forgot.php">Mot de passe oublié ?</a>
                </div>
                <div class="col-12 d-flex align-items-center mb-4">
                    <input class=mr-2" id="checked" type="checkbox" name="checked">
                    <span class="text-white text-nowrap">&nbsp;Se rappeler de moi</span>
                </div>
                <button type="submit" name="submit" class="btn red-btn text-white col-12">Se connecter</button>
            </form>
            <p class="text-white text-center">Pas encore inscrit ? <a class="text-nowrap" href="inscription.php">Enregistre toi !</a></p>
        </div>
    </div>
</div>

<?php

require_once "assets/php/require/footer.php";

?>
