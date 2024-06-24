<?php
include '../templates/config.php';

try {
    $id_prof = $user_id; 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['enunt']) && isset($data['id_solutie'])) {
            $id_solutie = $data['id_solutie'];
            $enunt = $data['enunt'];

            $query = "INSERT INTO observatii (id_prof, id_solutie, enunt) VALUES (:id_prof, :id_solutie, :enunt)
                      ON CONFLICT (id_solutie) DO UPDATE SET enunt = EXCLUDED.enunt, data_notare = CURRENT_TIMESTAMP";
            $statement = $db->prepare($query);
            $statement->bindParam(':id_prof', $id_prof);
            $statement->bindParam(':id_solutie', $id_solutie);
            $statement->bindParam(':enunt', $enunt);
            $statement->execute();

            echo json_encode(['success' => true]);
        }else if (isset($data['enunt']) && isset($data['id_propus'])) {
            $id_solutie = $data['id_propus'];
            $enunt = $data['enunt'];

            $query = "INSERT INTO observatii_propuse (id_prof, id_solutie, enunt) VALUES (:id_prof, :id_solutie, :enunt)
                      ON CONFLICT (id_solutie) DO UPDATE SET enunt = EXCLUDED.enunt, data_notare = CURRENT_TIMESTAMP";
            $statement = $db->prepare($query);
            $statement->bindParam(':id_prof', $id_prof);
            $statement->bindParam(':id_solutie', $id_solutie);
            $statement->bindParam(':enunt', $enunt);
            $statement->execute();

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Date invalide']);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
