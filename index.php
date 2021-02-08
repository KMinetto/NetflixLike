<?php

$title = 'Accueil';

require_once 'assets/php/require/header.php';
?>

<?php if (!empty($_SESSION)) : ?>
    <div class="col-12 mt-5 p-2 shadow">
        <div class="container">
            <div class="row">
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

                <?php if (isset($_SESSION['auth'])) : ?>
                    <h1 class="text-white text-center">Bonjour <?= $_SESSION['auth']->pseudo ?></h1>
                    <p class="text-white text-center">Qu'allez-vous regarder aujourd'hui ?</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
    <?php
        $sql = 'SELECT count(id) as nb_films FROM movies';
        $req = $pdo->prepare($sql);
        $req->execute();
        $nb_film = $req->fetch();
    ?>


    <div id="glider-container" class="glider-contain multiple">
        <h1 class="text-white text-center">Nos films</h1>
        <button class="glider-prev">
            <i class="fa fa-chevron-circle-left"></i>
        </button>
        <div id="glider" class="glider">
            <?php for ($i = 1; $i < $nb_film->nb_films; $i++) : ?>
                <?php
                    $sql = 'SELECT * FROM movies WHERE id = :id';
                    $req2 = $pdo->prepare($sql);
                    $req2->execute([":id" => $i]);
                    $movie = $req2->fetch();
                ?>
                <a href="movie.php?id=<?= $movie->id ?>">
                    <figure>
                        <img src="assets/img/movies/<?= $movie->img ?>" alt="Affiche de film">
                    </figure>
                </a>
            <?php endfor; ?>
        </div>
        <button class="glider-next">
            <i class="fa fa-chevron-circle-right"></i>
        </button>
        <div id="dots" class="glider-dots"></div>
    </div>

<?php
require_once 'assets/php/require/footer.php';
?>
