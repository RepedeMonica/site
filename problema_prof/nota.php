<?php
include '../templates/config.php';

try {
    $id_prof = $user_id; 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['nota']) && isset($data['id_solutie'])) {
            $id_solutie = $data['id_solutie'];
            $nota = $data['nota'];

            $query = "INSERT INTO note (id_prof, id_solutie, nota) VALUES (:id_prof, :id_solutie, :nota)
                      ON CONFLICT (id_solutie) DO UPDATE SET nota = EXCLUDED.nota, data_notare = CURRENT_TIMESTAMP";
            $statement = $db->prepare($query);
            $statement->bindParam(':id_prof', $id_prof);
            $statement->bindParam(':id_solutie', $id_solutie);
            $statement->bindParam(':nota', $nota);
            $statement->execute();

            echo json_encode(['success' => true]);
        }
        else if (isset($data['nota']) && isset($data['id_propus'])) {
            $id_solutie = $data['id_propus'];
            $nota = $data['nota'];

            $query = "INSERT INTO note_propuse (id_prof, id_solutie, nota) VALUES (:id_prof, :id_solutie, :nota)
                      ON CONFLICT (id_solutie) DO UPDATE SET nota = EXCLUDED.nota, data_notare = CURRENT_TIMESTAMP";
            $statement = $db->prepare($query);
            $statement->bindParam(':id_prof', $id_prof);
            $statement->bindParam(':id_solutie', $id_solutie);
            $statement->bindParam(':nota', $nota);
            $statement->execute();

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Date invalide']);
        }
    } elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
        if(isset($_GET['id_propus'])){
            $id_solutie = $_GET['id_propus'];
        $query = "SELECT nota FROM note_propuse WHERE id_solutie = :id_solutie";
        $statement = $db->prepare($query);
        $statement->bindParam(':id_solutie', $id_solutie);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode(['rating' => $result['nota']]);
        } else {
            echo json_encode(['rating' => 0]);
        }
        }
        else{
        $id_solutie = $_GET['id_solutie'];
        $query = "SELECT nota FROM note WHERE id_solutie = :id_solutie";
        $statement = $db->prepare($query);
        $statement->bindParam(':id_solutie', $id_solutie);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode(['rating' => $result['nota']]);
        } else {
            echo json_encode(['rating' => 0]);
        }
    }
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
