<?php
include  '../templates/config.php';

$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['text_solutie']) && isset($data['id_problema'])) {
    $solutie_text = $data['text_solutie'];
    $id_problema = $data['id_problema'];
    $id_elev = $user_id;

    try {
        $query_insert_solution = "INSERT INTO solutii (id_elev, id_problema, solutie) VALUES (:id_elev, :id_problema, :solutie)";
        $statement_insert_solution = $db->prepare($query_insert_solution);
        $statement_insert_solution->bindParam(':id_elev', $id_elev);
        $statement_insert_solution->bindParam(':id_problema', $id_problema);
        $statement_insert_solution->bindParam(':solutie', $solutie_text);
        $statement_insert_solution->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Eroare la inserarea solutiei: ' . $e->getMessage()]);
    }
} else if(isset($data['text_solutie']) && isset($data['id_problema_propusa'])) {
    $solutie_text = $data['text_solutie'];
    $id_problema = $data['id_problema_propusa'];
    $id_elev = $user_id;

    try {
        $query_insert_solution = "INSERT INTO solutii_propuse (id_elev, id_problema, solutie) VALUES (:id_elev, :id_problema, :solutie)";
        $statement_insert_solution = $db->prepare($query_insert_solution);
        $statement_insert_solution->bindParam(':id_elev', $id_elev);
        $statement_insert_solution->bindParam(':id_problema', $id_problema);
        $statement_insert_solution->bindParam(':solutie', $solutie_text);
        $statement_insert_solution->execute();

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Eroare la inserarea solutiei: ' . $e->getMessage()]);
    }
}

else {
    echo json_encode(['error' => 'Date invalide']);
}
?>
