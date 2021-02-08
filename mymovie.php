<?php
    $title = 'Ajouter un film';
    require_once "assets/php/require/header.php";
    if (empty($_SESSION['auth'])) {
        header('Location: index.php');
    }

    $sql = 'SELECT * FROM movies, users WHERE id_user = users.id';
    $req = $pdo->prepare($sql);
    $req->execute();
    $movies = $req->fetchAll();
?>

<h1 class="text-white text-center mt-5 ">Ajoutez ou modifiez un film !</h1>
<?php if (empty($movies)) : ?>
    <div class="myfilms">
        <p class="text-center text-white">Vous n'avez enregistré aucun film</p>
    </div>
<?php endif; ?>
<!--<div>-->


<?php foreach ($movies as $movie) : ?>
<!--    <div class="col-sm-1">-->
        <figure class="snip1104">
            <img src="assets/img/movies/<?= $movie->img ?>" alt="Affiche du film : <?= $movie->img ?>" />
            <figcaption>
                <h2>Modifier</h2>
            </figcaption>
            <a href="modifymovie.php?id=<?= $movie->id ?>"></a>
        </figure>
<!--    </div>-->
<?php endforeach; ?>
<!--</div>-->
<div class="myfilms d-flex justify-content-center">
    <a href="addmovie.php" class="col-2 text-white text-center mt-5">
        <button class="btn red-btn text-white">Ajouter un film</button>
    </a>
</div>

<?php
    require_once "assets/php/require/footer.php";
