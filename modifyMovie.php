<?php

$title = 'Modifier un film';
require_once "assets/php/require/header.php";

if (empty($_SESSION['auth'])) {
    header('Location: index.php');
}

$title = htmlspecialchars($_GET['title']);

$sql = 'SELECT * FROM movies WHERE title = :title';
$req = $pdo->prepare($sql);
$req->execute([':title' => $title]);
$movie = $req->fetch();

$localisation = '';
if (isset($_POST['submit'])) {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === 0 && $_FILES['file']['size'] <= 3_000_000) {
        $imageInfo = pathinfo($_FILES['file']['name']);
        $extension = $imageInfo['extension'];
        $arrayExtension = [
            'png',
            'jpg',
            'PNG',
            'jpeg',
        ];

        if (in_array($extension, $arrayExtension, true)) {
                $localisation = $_POST['title'] . '.' .$extension;
            $uploads = move_uploaded_file($_FILES['file']['tmp_name'], 'assets/img/movies/'. $localisation);
        } else {
            $_SESSION['flash']['errors'] = 'L\'image n\'a pas une bonne extension, Vérifiez que ce soit un .png ou un .jpg';
        }
    } else {
        $_SESSION['flash']['errors'] = 'Vous devez obligatoirement ajouter une affiche de film';
    }

    if (empty($_POST['title'])) {
        $_SESSION['flash']['errors'] = 'Le champ "titre" n\' a pas été correctement rempli';
    }

    if (empty($_POST['gender'])) {
        $_SESSION['flash']['errors'] = 'Le champ "genre" n\' a pas été correctement rempli';
    }

    if (empty($_POST['synopsis'])) {
        $_SESSION['flash']['errors'] = 'Le champ "synopsis" n\'a pas été correctement rempli';
    }

    if (empty($_SESSION['flash']['errors'])) {
        $sql = 'UPDATE movies 
                SET title = :title, gender = :gender, synopsis = :synopsis, id_user = :id_user, img = :img
                WHERE id = :id';
        $req = $pdo->prepare($sql);
        $req->execute([
            ':title' => $_POST['title'],
            ':gender' => $_POST['gender'],
            ':synopsis' => $_POST['synopsis'],
            ':id_user' => $_SESSION['auth']->id,
            ':img' => $localisation,
            ':id' => $movie->id
        ]);

        $_SESSION['flash']['success'] = 'Votre film a bien été modifié !';
        header('Location: myMovie.php');
        exit();
    }
}

?>

<div class="container">
    <div class="row">
        <h1 class="text-white mt-5">Modifier le film</h1>
        <div class="col-12 col-lg-6 d-lg-flex mt-5">
            <div>
                <img width="100%" height="auto" src="assets/img/movies/<?= $movie->img ?>" alt="Image du film: <?= $movie->title ?>">
            </div>
            <div>
                <h2 class="text-center text-white p-2"><?= $movie->title ?></h2>
                <p class="text-white text-center p-2"><?= $movie->gender ?></p>
                <p class="text-white text-center p-2"><?= $movie->synopsis ?></p>
            </div>
        </div>
        <div class="col-12 col-lg-6 my-5">
            <form action="" method="post" enctype="multipart/form-data">
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
                <div class="col-12 mb-4">
                    <label for="file" class="text-white col-12">Nouvelle image <small>*</small></label>
                    <input class="text-white" type="file" name="file" id="file">
                </div>
                <div class="col-12 mb-4">
                    <label for="title" class="text-white">Nouveau titre</label>
                    <input type="text" name="title" id="title" class="form-control" value="<?= $movie->title ?>" placeholder="Nouveau titre">
                </div>
                <div class="col-12 mb-4">
                    <label for="gender" class="text-white">Nouveau genre</label>
                    <input type="text" name="gender" id="gender" class="form-control" placeholder="Nouveau genre" value="<?= $movie->gender ?>">
                </div>
                <div class="col-12 mb-4">
                    <label for="synopsis" class="text-white">Nouveau synopsis</label>
                    <textarea name="synopsis" class="form-control" id="synopsis" cols="30" rows="4"
                              placeholder="Nouveau synopsis"><?= $movie->synopsis ?></textarea>
                </div>
                <button type="submit" name="submit" class="btn red-btn text-white col-12">Modifier le film</button>
            </form>
        </div>
    </div>
</div>

<?php

require_once "assets/php/require/footer.php";
