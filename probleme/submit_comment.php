<?php
include  '../templates/config.php';

$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['text_comentariu']) && isset($data['id_problema'])) {
    $id_problema = $data['id_problema'];
    $comentariu = $data['text_comentariu'];

    try {
        $query_insert_solution = "INSERT INTO comentarii (id_problema, comentariu) VALUES (:id_problema, :comentariu)";
        $statement_insert_solution = $db->prepare($query_insert_solution);
        $statement_insert_solution->bindParam(':id_problema', $id_problema);
        $statement_insert_solution->bindParam(':comentariu', $comentariu);
        $statement_insert_solution->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Eroare la inserarea comentariului: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Date invalide']);
}
?>
