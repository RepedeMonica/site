<?php
include '../templates/config.php';

try {
  $id_elev = $user_id;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $data = json_decode(file_get_contents('php://input'), true);
       if (isset($data['rating']) && isset($data['id_problema'])) {
        $id_problema = $data['id_problema'];
        $rating = $data['rating'];

        $query = "INSERT INTO ratings (id_elev, id_problema, rating) VALUES (:id_elev, :id_problema, :rating)
                  ON CONFLICT (id_elev, id_problema) DO UPDATE SET rating = EXCLUDED.rating";
        $statement = $db->prepare($query);
        $statement->bindParam(':id_elev', $id_elev);
        $statement->bindParam(':id_problema', $id_problema);
        $statement->bindParam(':rating', $rating);
        $statement->execute();

        echo json_encode(['success' => true]);
    }
    else {
        echo json_encode(['error' => 'Date invalide']);
    }
    } elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
        $id_problema = $_GET['id_problema'];

        $query = "SELECT rating FROM ratings WHERE id_elev = :id_elev AND id_problema = :id_problema";
        $statement = $db->prepare($query);
        $statement->bindParam(':id_elev', $id_elev);
        $statement->bindParam(':id_problema', $id_problema);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode(['rating' => $result['rating']]);
        } else {
            echo json_encode(['rating' => 0]);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
