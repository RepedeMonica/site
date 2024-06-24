<?php
$host = 'localhost';
$dbname = 'usersdb';
$user = 'postgres';
$password = 'student';

try {
    $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $nume = $_POST["nume"];
        $prenume = $_POST["prenume"];
        $email = $_POST["email"];
        $telefon = $_POST["telefon"];
        $tara = $_POST["tara"];
        $oras = $_POST["oras"];
        $liceu = $_POST["liceu"];
        $clasa = $_POST["clasa"];
        $tip_utilizator = $_POST["tip_utilizator"];

        $query_check_username = "SELECT * FROM users WHERE username = :username";
        $statement_check_username = $db->prepare($query_check_username);
        $statement_check_username->bindParam(':username', $username);
        $statement_check_username->execute();

        if ($statement_check_username->rowCount() > 0) {
            $error_message = "Numele de utilizator este deja folosit. Vă rugăm să alegeți altul.";
            header("Location: pagina_fara_cont.html?error=" . urlencode($error_message));
            exit();
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $query_insert_user = "INSERT INTO users (username, password, nume, prenume, email, telefon, tara, oras, liceu, clasa, tip_utilizator) 
                      VALUES (:username, :password, :nume, :prenume, :email, :telefon, :tara, :oras, :liceu, :clasa, :tip_utilizator)";
            $statement_insert_user = $db->prepare($query_insert_user);
            $statement_insert_user->execute(array(
                ':username' => $username,
                ':password' => $hashed_password,
                ':nume' => $nume,
                ':prenume' => $prenume,
                ':email' => $email,
                ':telefon' => $telefon,
                ':tara' => $tara,
                ':oras' => $oras,
                ':liceu' => $liceu,
                ':clasa' => $clasa,
                ':tip_utilizator' => $tip_utilizator
            ));

            header("Location: ../home.html");
            exit();
        }
    }
} catch (PDOException $e) {
    $error_message = "Eroare la conectare: " . $e->getMessage();
    header("Location: pagina_fara_cont.html?error=" . urlencode($error_message));
    exit();
}
?>

