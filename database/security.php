<?php

class AuthDB
{

    private PDOStatement $statementRegister;
    private PDOStatement $statementReadSession;
    private PDOStatement $statementReadUSer;
    private PDOStatement $statementReadUserEmail;
    private PDOStatement $statementCreateSession;
    private PDOStatement $statementDeleteSession;

    function __construct(private PDO $pdo)
    {
        $this->statementRegister = $pdo->prepare('
        INSERT INTO user VALUES(
            DEFAULT,
            :firstname,
            :lastname,
            :email,
            :password
        )
    ');
        $this->statementReadSession = $pdo->prepare('SELECT * FROM session WHERE id=:id');

        $this->statementReadUSer = $pdo->prepare('SELECT * FROM user WHERE id=:id');

        $this->statementReadUserEmail =   $pdo->prepare('
       SELECT * FROM user WHERE email = :email
       ');

        $this->statementCreateSession = $pdo->prepare('INSERT INTO session VALUES (
        :sessionId,
        :userid
        )');

        $this->statementDeleteSession = $pdo->prepare('DELETE FROM session WHERE id=:id');
    }

    function login(string $userId)
    {
        $sessionId = bin2hex(random_bytes(32));
        $this->statementCreateSession->bindValue(':userid', $userId);
        $this->statementCreateSession->bindValue(':sessionId', $sessionId);
        $this->statementCreateSession->execute();
        $signature = hash_hmac('sha256', $sessionId, "Petit con");
        setcookie('session', $sessionId, time() + 60 * 60 * 24 * 14, "", "", false, true);
        setcookie('signature', $signature, time() + 60 * 60 * 24 * 14, "", "", false, true);
    }

    function register(array $user)
    {
        $hashedPassword = password_hash($user['password'], PASSWORD_ARGON2I);
        $this->statementRegister->bindValue(':firstname', $user['firstname']);
        $this->statementRegister->bindValue(':lastname', $user['lastname']);
        $this->statementRegister->bindValue(':email', $user['email']);
        $this->statementRegister->bindValue(':password', $hashedPassword);
        $this->statementRegister->execute();
        return;
    }

    function isLoggedin()
    {
        $sessionId = $_COOKIE['session'] ?? "";
        if ($sessionId) {
            $this->statementReadSession->bindValue(':id', $sessionId);
            $this->statementReadSession->execute();
            $session = $this->statementReadSession->fetch();
            if ($session) {
                $this->statementReadUSer->bindValue(':id', $session['userid']);
                $this->statementReadUSer->execute();
                $user = $this->statementReadUSer->fetch();
            }
        }
        return $user ?? false;
    }

    function logout(string $sessionId)
    {
        $this->statementDeleteSession->bindValue(':id', $sessionId);
        $this->statementDeleteSession->execute();
        setcookie('session', time() - 1);
        return;
    }

    function getUserEmail(string $email)
    {
        $this->statementReadUserEmail->bindValue(':email', $email);
        $this->statementReadUserEmail->execute();
        return $this->statementReadUserEmail->fetch();
    }
}

return new AuthDB($pdo);

// function isLoggedin()
// {
//     global $pdo;
//     $sessionId = $_COOKIE['session'] ?? "";
//     if($sessionId) {
//         $statementSession = $pdo->prepare('SELECT * FROM session WHERE id=:id');
//         $statementSession->bindValue(':id', $sessionId);
//         $statementSession->execute();
//         $session = $statementSession->fetch();
//         if($session){
//             $statementUser = $pdo->prepare('SELECT * FROM user WHERE id=:id');
//             $statementUser->bindValue(':id', $session['userid']);
//             $statementUser->execute();
//             $user = $statementUser->fetch();
//         }

//     }
//     return $user ?? false;
// }