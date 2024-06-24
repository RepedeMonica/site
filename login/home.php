<?php
require_once __DIR__ . "/../vendor/autoload.php"; 
use Firebase\JWT\JWT;

$host = 'localhost'; 
$dbname = 'usersdb';
$user = 'postgres'; 
$password = 'student';
$key = "cheia_ta_secreta";  

session_start();

try {
    $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $query = "SELECT * FROM users WHERE username = :username";
        $statement = $db->prepare($query);
        $statement->bindParam(':username', $username);

        $statement->execute();

        if ($statement->rowCount() > 0) {
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $storedPassword = $user['password'];

                if (password_verify($password, $storedPassword)) {
                    autentificare($user, $key);
                } elseif ($password === $storedPassword) {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    $updateQuery = "UPDATE users SET password = :password WHERE id = :id";
                    $updateStatement = $db->prepare($updateQuery);
                    $updateStatement->bindParam(':password', $hashedPassword);
                    $updateStatement->bindParam(':id', $user['id']);
                    $updateStatement->execute();

                    autentificare($user, $key);
                } else {
                    $_SESSION['error'] = "Autentificare eșuată. Nume de utilizator sau parolă incorecte.";
                    header("Location: ../home.html");
                    exit();
                }
            }
        } else {
            $_SESSION['error'] = "Autentificare eșuată. Nume de utilizator sau parolă incorecte.";
            header("Location: ../home.html");
            exit();
        }
    }
} catch (PDOException $e) {
    echo "Eroare la conectare: " . $e->getMessage();
}

function autentificare($user, $key) {
    $payload = [
        "iss" => "https://MyProject.com",
        "iat" => time(),
        "exp" => time() + 3600, 
        "data" => [
            "username" => $user['username'],
            "nume" => $user['nume'],
            "prenume" => $user['prenume'],
            "email" => $user['email'],
            "clasa" => $user['clasa'],
            "tip_utilizator" => $user['tip_utilizator'],
            "user_id" => $user['id']
        ]
    ];
    $jwt = JWT::encode($payload, $key, 'HS256');

    setcookie("jwt_token", $jwt, time() + 3600, "/"); 

    if ($user['tip_utilizator'] == 'admin') {
        header("Location:../admin/pagina_administrator.html");
    } else if ($user['tip_utilizator'] == 'elev' || $user['tip_utilizator'] == 'profesor') {
        header("Location: ../baraNavigare/index.html");
    }
    exit();
}
?>
