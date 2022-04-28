<?php
$currentUser = $currentUser ?? false;
?>


<header class="main-head">
    <a href="/" class="home"><span>Blog</span></a>
    <ul class="nav-items">
        <?php if($currentUser) : ?>
            <li class="nav-item"><a href="FormArticle.php">Ajouter un article</a></li>
            <li class="nav-item"><a href="/profile.php"><?= $currentUser['firstname'][0] . $currentUser['lastname'][0] ?></a></li>
            <li class="nav-item"><a href="/auth-logout.php">Déconnexion</a></li>
        <?php else : ?>
            <li class="nav-item"><a href="/auth-register.php">Inscription</a></li>
            <li class="nav-item"><a href="/auth-login.php">Connexion</a></li>
        <?php endif; ?>
    </ul>
</header>