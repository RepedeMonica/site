<?php
include '../templates/config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $titlu = $_POST['nume_problema'];
        $taguri = $_POST['taguri'];
        $categorie = $_POST['tag'];
        $enunt = $_POST['cerinta'];

        $sql = "INSERT INTO probleme_propuse (titlu, tag, categorie, enunt) VALUES (:titlu, :taguri, :categorie, :enunt)";
        $stmt = $db->prepare($sql);
        $stmt->execute(array(
            ':titlu' => $titlu,
            ':taguri' => $taguri,
            ':categorie' => $categorie, 
            ':enunt' => $enunt
        ));

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>
