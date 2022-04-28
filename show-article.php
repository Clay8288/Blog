<?php

require_once __DIR__ . '/database/database.php';
$authDB = require_once __DIR__ . '/database/security.php';

$currentUser = $authDB->isLoggedin();

$articlesDB = require_once './database/models/ArticleDB.php';
$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';

if (!$id) {

    header('Location = /');

} else {
   $article = $articlesDB->fetchOne($id);
}


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once __DIR__ . '/templates/head.php' ?>
    <link rel="stylesheet" href="/public/css/show-article.css">

    <title>Article</title>
</head>

<body>
    <div class="container">

        <?php require_once __DIR__ . "/templates/header.php" ?>

        <main class="content">
            <div class="main-article">
                <a href="/" class="return">Retour à la liste des articles</a>
                <div class="article-cover-image" style="background-image: url(<?= $article['image'] ?>);"></div>
                <h1 class="article-title"><?= $article['title'] ?></h1>
                <div class="separator"></div>
                <div class="article-content"><?= $article['content'] ?></div>
                <p class="article-author"><?= $article['firstname'] . ' ' . $article['lastname'] ?></p>
                <?php if($currentUser && $currentUser['id'] === $article['author'] ): ?>
                <div class="action">
                    <a href="FormArticle.php?id=<?= $article['id'] ?>">Éditer</a>
                    <a href="DeleteArticle.php?id=<?= $article['id'] ?>">Supprimer</a>
                </div>
                <?php endif; ?>
            </div>
        </main>
        <?php require_once __DIR__ . "/templates/footer.php" ?>
    </div>
</body>

</html>