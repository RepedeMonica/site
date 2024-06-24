<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'usersdb';
$user = 'postgres';
$password = 'student';
try {
    $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT id, titlu, tag,categorie, enunt FROM probleme";
    $statement = $db->prepare($query);
    $statement->execute();
    $probleme = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($probleme);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
