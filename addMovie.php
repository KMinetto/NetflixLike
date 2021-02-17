<?php

    $title = "Ajouter un film";
    require_once "assets/php/require/header.php";

    if (empty($_SESSION['auth'])) {
        header('Location: index.php');
        exit();
    }

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
            $sql = 'INSERT INTO movies SET title = :title, gender = :gender, synopsis = :synopsis, id_user = :id_user, img = :img';
            $req = $pdo->prepare($sql);
            $req->execute([
                ':title' => $_POST['title'],
                ':gender' => $_POST['gender'],
                ':synopsis' => $_POST['synopsis'],
                ':id_user' => $_SESSION['auth']->id,
                'img' => $localisation
            ]);

            $_SESSION['flash']['success'] = 'Votre film a bien été ajouté !';
        }
    }
?>

    <div class="col-12 col-md-4 position-absolute top-50 start-50 translate-middle p-3 px-sm-5 py-sm-5 shadow">
        <div class="container">
            <div class="row">
                <form class="mb-3" action="" method="post" enctype="multipart/form-data">
                    <h1 class="text-white mb-4">Ajouter un film</h1>
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
                        <input class="text-white" type="file" name="file" id="file">
                    </div>
                    <div class="col-12 mb-4">
                        <input type="text" name="title" class="form-control" placeholder="Titre du film">
                    </div>
                    <div class="col-12 mb-4">
                        <input type="text" name="gender" class="form-control" placeholder="Genre du film">
                    </div>
                    <div class="col-12 mb-4">
                        <textarea name="synopsis" class="form-control" id="synopsis" cols="30" rows="4" placeholder="Synopsis"></textarea>
                    </div>
                    <button type="submit" name="submit" class="btn red-btn text-white col-12">Ajouter un film</button>
                </form>
            </div>
        </div>
    </div>
<?php
require_once "assets/php/require/footer.php";
