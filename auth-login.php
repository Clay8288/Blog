<?php

require_once './database/database.php';
$authDB = require_once __DIR__ . '/database/security.php';

const ERROR_REQUIRED = "Veuillez renseigner ce champ";
const ERROR_EMAIL_INVALID = "L'email n'est pas valide";
const ERROR_PASSWORD_MISMATCH = "Le mot de passe n'est pas valide";
const ERROR_EMAIL_UNKNOW = "L'email n'est pas enregistré";


$errors = [
    'email' => "",
    'password' => ""
];

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $input = filter_input_array(INPUT_POST, [
        'email' => FILTER_SANITIZE_EMAIL,
    ]);
    $email = $input['email'] ?? "";
    $password = $_POST['password'] ?? "";


    if (!$email) {
        $errors['email'] = ERROR_REQUIRED;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = ERROR_EMAIL_INVALID;
    }

    if (!$password) {
        $errors['password'] = ERROR_REQUIRED;
    }


    if (empty(array_filter($errors, fn ($e) => $e !== ""))) {
        $user = $authDB->getUserEmail($email);

        if (!$user) {
            $errors['email'] = ERROR_EMAIL_UNKNOW;
        } else {
            if (!password_verify($password, $user['password'])) {
                $errors['password'] = ERROR_PASSWORD_MISMATCH;
            } else {
                $authDB->login($user['id']);
                header('Location: /');
            }
        }
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once __DIR__ . '/templates/head.php' ?>
    <link rel="stylesheet" href="/public/css/login.css">
    <title>Connexion</title>
</head>

<body>
    <div class="container">

        <?php require_once __DIR__ . "/templates/header.php" ?>

        <main class="content">
            <div class="block">
                <h2>Inscription</h2>
                <form action="/auth-login.php" , method="POST">
                    <div class="form-control">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email"><?= $email ?? "" ?></input>
                        <?php if ($errors['email']) : ?>
                            <p class="text-error"><?= $errors['email'] ?></p>
                        <?php endif ?>
                    </div>
                    <div class="form-control">
                        <label for="password">Mot de passe</label>
                        <input type="password" name="password" id="password"></input>
                        <?php if ($errors['password']) : ?>
                            <p class="password-error"><?= $errors['password'] ?></p>
                        <?php endif ?>
                    </div>
                    <div class="form-action">
                        <a href class="btn-cxl">Annnuler</a>
                        <button class="btn-save" type="submit">Valider</button>
                    </div>
                </form>
            </div>
        </main>
        <?php require_once __DIR__ . "/templates/footer.php" ?>
    </div>
</body>

</html>