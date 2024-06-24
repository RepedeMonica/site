<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'usersdb';
$user = 'postgres';
$password = 'student';
try {
    if (isset($_GET['id'])){
    $id = htmlspecialchars($_GET['id']);
    
    $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT id, titlu, tag,categorie, enunt FROM probleme where id =:id";
    $statement = $db->prepare($query);
    $statement->execute(array(':id' => $id));
    $probleme = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($probleme);
}
else if (isset($_GET['categorie'])){
    $id = htmlspecialchars($_GET['categorie']);
    
    $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT id, titlu, tag,categorie, enunt FROM probleme where categorie =:id";
    $statement = $db->prepare($query);
    $statement->execute(array(':id' => $id));
    $probleme = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($probleme);
}
else if (isset($_GET['tag'])){
    $id = htmlspecialchars($_GET['tag']);
    
    $db = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT id, titlu, tag,categorie, enunt FROM probleme where tag =:id";
    $statement = $db->prepare($query);
    $statement->execute(array(':id' => $id));
    $probleme = $statement->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($probleme);
}
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
