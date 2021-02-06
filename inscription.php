<?php

$title = "Inscription";
require_once "assets/php/require/header.php";

if (!empty($_SESSION['auth'])) {
    header('Location: index.php');
}

if (isset($_POST['submit'])) {
    if (!empty($_POST['pseudo'])) {
        $pseudo = htmlspecialchars($_POST['pseudo']);
        if (strlen($pseudo) > 20) {
            $_SESSION['flash']['errors'] = 'Votre pseudo ne doit pas dépasser 20 caractères';
        } else {
            $sql = 'SELECT * FROM netflix_like.users WHERE pseudo = :pseudo';
            $req = $pdo->prepare($sql);
            $req->execute([":pseudo" => $pseudo]);
            $user = $req->fetch();

            if ($user) {
                $_SESSION['flash']['errors'] = 'Ce pseudo est déjà pris par un autre utilisateur';
            }
        }
    } else {
        $_SESSION['flash']['errors'] = 'Vous n\'avez pas rempli les champs correctement';
    }

    if (!empty($_POST['email'])) {
        $email = htmlspecialchars($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash']['errors'] = 'Vous devez avoir une adresse mail valide';
        } else {
            $sql = 'SELECT * FROM netflix_like.users WHERE email = :email';
            $req = $pdo->prepare($sql);
            $req->execute([":email" => $email]);
            $user = $req->fetch();

            if ($user) {
                $_SESSION['flash']['errors'] = 'Cet adresse mail est déjà utilisée par un autre utilisateur';
            }
        }
    } else {
        $_SESSION['flash']['errors'] = 'Vous n\'avez pas rempli les champs correctement';
    }

    if (empty($_POST['password'])) {
        $password = htmlspecialchars($_POST['password']);
        $_SESSION['flash']['errors'] = 'Vous n\'avez pas rempli les champs correctement';
    } else if (strlen($_POST['password']) < 8) {
        $_SESSION['flash']['errors'] = 'Votre mot de passe doit contenir au minimum 8 caractères';
    }

    if (!empty($_POST['passwordConfirm'])) {
        $passwordConfirm = htmlspecialchars($_POST['passwordConfirm']);
        if ($passwordConfirm !== $_POST['password']) {
            $_SESSION['flash']['errors'] = 'Les mots de passes rentrés ne sont pas identiques';
        }
    } else {
        $_SESSION['flash']['errors'] = 'Vous n\'avez pas rempli les champs correctement';
    }

    if (empty($_SESSION['flash']['errors'])) {
        $passwordHash = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $token = token(60);

        $sql = 'INSERT INTO netflix_like.users SET pseudo = :pseudo, email = :email, password = :password, 
                                   confirmation_token = :confirmation_token, created_at = NOW()';
        $req = $pdo->prepare($sql);
        $req->execute([":pseudo" => $_POST['pseudo'], ":email" => $_POST['email'], ":password" => $passwordHash, ":confirmation_token" => $token]);
        $userId = $pdo->lastInsertId();

        mail($_POST['email'], 'Confirmation d\'inscription',
            'Afin de confirmer votre inscription, veuillez cliquer sur ce lien ci-dessous :
                              http://localhost/section18/confirmation.php?id='.$userId.'&token='.$token
        );
        $_SESSION['flash']['success'] = 'Un mail vous a été envoyé, veuillez confirmer votre inscription';
    }

}
?>

<div class="col-12 col-md-4 position-absolute top-50 start-50 translate-middle p-3 px-sm-5 py-sm-5 shadow">
    <div class="container">
        <div class="row">
            <form class="mb-3" action="" method="post">
                <h1 class="text-white">S'inscrire</h1>
                <?php if (isset($_SESSION['flash'])) : ?>
                    <?php if (isset($_SESSION['flash']['errors'])) :?>
                        <ul class="alert alert-danger p-0">
                            <?php foreach ($_SESSION['flash'] as $errors) :?>
                                <li class="text-center"><?= $errors ?></li>
                            <?php endforeach; ?>
                            <?php unset($_SESSION['flash']); ?>
                        </ul>
                    <?php elseif (isset($_SESSION['flash']['success'])) :?>
                        <ul class="alert alert-success p-0">
                            <?php foreach ($_SESSION['flash'] as $success) : ?>
                                <li class="text-center"><?= $success ?></li>
                            <?php endforeach; ?>
                            <?php unset($_SESSION['flash']) ?>
                        </ul>
                    <?php endif; ?>
                <?php endif ?>
                <div class="col-12 mb-4"><input type="text" name="pseudo" class="form-control" placeholder="Pseudo"></div>
                <div class="col-12 mb-4"><input type="email" name="email" class="form-control" placeholder="email"></div>
                <div class="col-12 mb-4"><input type="password" name="password" class="form-control" placeholder="Mot de passe"></div>
                <div class="col-12 mb-4"><input type="password" name="passwordConfirm" class="form-control" placeholder="Confirmation de mot de passe"></div>
                <button type="submit" name="submit" class="btn red-btn text-white col-12">S'inscrire</button>
            </form>
            <p class="text-white text-center">Vous avez déjà un compte ? <a class="text-nowrap" href="connection.php">Connectez vous !</a></p>
        </div>
    </div>
</div>

<?php

require_once "assets/php/require/footer.php";

?>
