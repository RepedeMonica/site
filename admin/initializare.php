<?php
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../jwt_utils.php";

$user_data = validate_jwt();

if (!$user_data) {
    header("Location: ../home.html");
    exit();
}

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
