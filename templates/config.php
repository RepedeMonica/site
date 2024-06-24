<?php
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../jwt_utils.php";

$user_data = validate_jwt();

if (!$user_data) {
    if (isset($_COOKIE['jwt'])) {
        setcookie('jwt', '', time() - 3600, '/');
    }
    
    header("Location: ../home.html");
    exit();
}

$username= $user_data->username;
$nume = $user_data->nume;
$prenume = $user_data->prenume;
$email = $user_data->email;
$clasa = $user_data->clasa;
$tip_utilizator = $user_data->tip_utilizator;
$user_id = $user_data->user_id;

$host = 'localhost';
$dbname = 'usersdb';
$user = 'postgres';
$password = 'student';

try {
$db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit();
}
?>
