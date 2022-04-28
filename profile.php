<?php

require_once __DIR__ . '/database/database.php';
$authDB = require_once __DIR__ . '/database/security.php';
$articleDB = require_once __DIR__ . '/database/models/ArticleDB.php';

$articles = [];
$currentUser = $authDB->isLoggedin();
if (!$currentUser) {
    header('Location: /');
}

$articles = $articleDB->fetchUserArticle($currentUser['id']);

// var_dump($articles);
// exit;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once __DIR__ . '/templates/head.php' ?>
    <link rel="stylesheet" href="/public/css/profile.css">
    <title>Mon profil</title>
</head>

<body>
    <div class="container">

        <?php require_once __DIR__ . "/templates/header.php" ?>

        <main class="content">
            <h1>Mon espace</h1>
            <h2>Mes informations</h2>
            <div>
                <ul>
                    <li>
                        <strong>Prénom :</strong>
                        <p><?= $currentUser['firstname'] ?></p>
                    </li>
                    <li>
                        <strong>Nom :</strong>
                        <p><?= $currentUser['lastname'] ?></p>
                    </li>
                    <li>
                        <strong>Email :</strong>
                        <p><?= $currentUser['email'] ?></p>
                    </li>
                </ul>
            </div>
            <h3>Mes articles</h3>
            <div>
                <ul>
                    <?php foreach ($articles as $article) : ?>
                        <li>
                            <p><?= $article['title'] ?></p>
                            <div class="action">
                                <a href="FormArticle.php?id=<?= $article['id'] ?>">Éditer</a>
                                <a href="DeleteArticle.php?id=<?= $article['id'] ?>">Supprimer</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </main>
        <?php require_once __DIR__ . "/templates/footer.php" ?>
    </div>
</body>

</html>