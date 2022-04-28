<?php

require_once __DIR__ . '/database/database.php';
$authDB = require_once __DIR__ . '/database/security.php';

$currentUser = $authDB->isLoggedin();
if ($currentUser) {
    $articlesDB = require_once './database/models/ArticleDB.php';

    $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id = $_GET['id'] ?? [];

    if ($id) {
        $article = $articlesDB->fetch($id);
        if ($article['author'] === $currentUser['id']) {
            $articlesDB->deleteOne($id);
        }
    }
}


header('Location: /');
