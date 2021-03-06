<?php

$filename = __DIR__ . "/data/article.json";
$articles = [];
$categories = [];

if (file_exists($filename)) {
    $articles = json_decode(file_get_contents($filename), true) ?? [];
    $categoriesList = array_map(fn ($article) => $article['category'], $articles);
    //print_r($categoriesList);

    $categories = array_reduce($categoriesList, function ($acc, $cat) {

        if (isset($acc[$cat])) {
            $acc[$cat]++;
        } else {
            $acc[$cat] = 1;
        }

        return $acc;
    }, []);
    //print_r($categories);

    $articleCategories = array_reduce($articles, function ($acc, $article) {
        if (isset($acc[$article['category']])) {
            $acc[$article['category']] = [...$acc[$article['category']], $article];
        } else {
            $acc[$article['category']] = [$article];
        }

        return $acc;
    }, []);
    //echo "<pre>";
    //print_r($articleCategories);
    //echo "</pre>";

}

//echo count($articles);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once __DIR__ . '/templates/head.php' ?>

    <title>Blog</title>
</head>

<body>
    <div class="container">

        <?php require_once __DIR__ . "/templates/header.php" ?>
        
        <main class="content">
            <div class="category-container">
                <?php foreach ($categories as $categoryKey => $category) : ?>
                    <h2><?= $categoryKey ?></h2>
                    <div class="articles-container">
                        <?php foreach ($articleCategories[$categoryKey] as $article) : ?>
                            <a href="/show-article.php?id=<?= $article['id'] ?>" class="article">
                                <div class="mouse-hover">
                                    <div class="img-container" style="background-image: url(<?= $article['image'] ?>)">
                                    </div>
                                </div>
                                <h3><?= $article['title'] ?></h3>
                        </a>
                        <?php endforeach; ?>
                    </div>

                <?php endforeach ?>
            </div>
        </main>
        <?php require_once __DIR__ . "/templates/footer.php" ?>
    </div>
</body>

</html>