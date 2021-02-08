<?php
$title = "Film";
require_once "assets/php/require/header.php";

// Récupération de film
$id = $_GET['id'];
$sql = 'SELECT * FROM movies WHERE id = :id';
$req = $pdo->prepare($sql);
$req->execute([":id" => $id]);
$movie = $req->fetch();

// Commentaires
if (isset($_POST['submit'])) {
    $comment = htmlspecialchars($_POST['comment']);
    if (!empty($_SESSION['auth'])) {
        if (!empty($comment)) {
            $sql = "INSERT INTO comment SET id_user = :id_user, id_movie = :id_movie, 
                        comment = :comment, date_comment = NOW()";
            $req = $pdo->prepare($sql);
            $req->execute([
                ":id_user" => $_SESSION['auth']->id,
                ":id_movie" => $movie->id,
                ':comment' => $comment
            ]);
            $_SESSION['flash']['success'] = 'Votre commentaire a bien été envoyé';
        } else {
            $_SESSION['flash']['errors'] = 'Vous devez écrire un commentaire pour pouvoir le poster';
        }
    } else {
        $_SESSION['flash']['errors'] = 'Vous devez être connecté(e) pour envoyer un commentaire';
    }
}
?>

<?php
$sql = 'SELECT comment.id, comment.id_user, comment.id_movie, comment.comment, comment.date_comment, users.pseudo, users.id
        FROM comment, users
        WHERE id_user = users.id AND id_movie = :id_movie
        ORDER BY comment.id DESC LIMIT 5';
$req2 = $pdo->prepare($sql);
$req2->execute([":id_movie" => $id]);
$comments = $req2->fetchAll()
?>
    <img class="w-50 offset-3 mt-5" src="assets/img/movies/<?= $movie->img ?>" alt="Affiche du film : <?= $movie->title ?>">
    <div class="shadow">
        <h1 class="text-white text-center mt-4 title"><?= $movie->title ?></h1>
        <div class="col-12 text-white text-center p-5">
            <h2>Synopsis</h2>
            <p><?= $movie->synopsis ?></p>
        </div>
        <div class="text-white p-5">
            <h2 class="text-center">Commentaire(s) :</h2>
            <?php foreach ($comments as $comment) :?>
            <div class="col-12 d-sm-flex comments mt-2 p-2">
                <div class="d-flex flex-column text-nowrap">
                    <span><?= $comment->pseudo ?></span>
                    <span><?= $comment->date_comment ?></span>
                </div>
                <div class="p-2 d-flex w-100 justify-content-sm-end">
                    <p class="comment-content"><?= $comment->comment ?></p>
                </div>
            </div>
            <?php endforeach;?>
            <form action="" method="post" class="mt-4 mb-5">
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
                <textarea class="col-12 p-2" name="comment" id="comment" cols="15" rows="5" placeholder="Votre commentaire"></textarea>
                <button class="btn red-btn text-white" type="submit" name="submit">Publier</button>
            </form>
        </div>
    </div>

<?php
require_once "assets/php/require/footer.php";
