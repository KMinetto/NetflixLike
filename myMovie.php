<?php
    $title = 'Ajouter un film';
    require_once "assets/php/require/header.php";
    if (empty($_SESSION['auth'])) {
        header('Location: index.php');
    }

    $sql = 'SELECT * FROM movies, users WHERE users.id = :id AND id_user = users.id';
    $req = $pdo->prepare($sql);
    $req->execute([":id" => $_SESSION['auth']->id]);
    $movies = $req->fetchAll();
?>

<h1 class="text-white text-center mt-5 ">Ajoutez ou modifiez un film !</h1>

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

<?php if (empty($movies)) : ?>
    <div class="myfilms">
        <p class="text-center text-white">Vous n'avez enregistré aucun film</p>
    </div>
<?php endif; ?>
<!--<div>-->


<?php foreach ($movies as $movie) : ?>
<!--    <div class="col-sm-1">-->
        <figure class="snip1104">
            <img src="assets/img/movies/<?= $movie->img ?>" alt="Affiche du film : <?= $movie->title ?>" />
            <figcaption>
                <h2>Modifier</h2>
            </figcaption>
            <a href="modifyMovie.php?title=<?= $movie->title ?>&gender=<?= $movie->gender ?>&synopsis=<?= $movie->synopsis ?>
                &img=<?= $movie->img ?>id_user=<?= $movie->user_id ?>"></a>
        </figure>
<!--    </div>-->
<?php endforeach; ?>
<!--</div>-->
<div class="myfilms d-flex justify-content-center mb-5">
    <a href="addMovie.php" class="col-2 text-white text-center mt-5">
        <button class="btn red-btn text-white">Ajouter un film</button>
    </a>
</div>

<?php
    require_once "assets/php/require/footer.php";
