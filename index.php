<?php

$title = 'Accueil';

require_once 'assets/php/require/header.php';
?>

<?php if (!empty($_SESSION)) : ?>
    <div class="col-12 col-md-4 position-absolute top-50 start-50 translate-middle p-2 shadow">
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
                    <h1 class="text-white">Bonjour <?= $_SESSION['auth']->pseudo ?></h1>
                    <p class="text-white">Qu'allez-vous regarder aujourd'hui ?</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php
require_once 'assets/php/require/footer.php';
?>
