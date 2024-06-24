<?php
include 'initializare.php';

try {
    $id = 0;
    if(isset($_GET['id'])) 
    $id = intval($_GET['id']);

    $query = "SELECT id, titlu, tag, categorie, enunt FROM probleme_propuse ORDER BY id LIMIT 1 OFFSET :id";//incep de la poz id si returnez DOAR  o int
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $problema = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($problema);
    exit();
} catch (PDOException $e) {
    echo json_encode([]);
    exit();
}
?>
