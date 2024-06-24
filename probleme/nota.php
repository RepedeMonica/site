<?php
include '../templates/config.php';

try {
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $id_propus = null;
        $id_problema = null;
        if(isset($_GET['id_propus'])){ $id_propus = $_GET['id_propus'];}
        else $id_problema = $_GET['id_solutie'];
        
        if($id_propus){
            $query="SELECT s.id AS id,n.nota AS nota, n.data_notare AS data_notare from solutii_propuse s 
        JOIN note_propuse n on n.id_solutie = s.id
        WHERE s.id_elev = :id_elev AND s.id_problema = :id_problema
        ORDER BY n.data_notare DESC LIMIT 1";
        $statement = $db->prepare($query);
        $statement->bindParam(':id_elev', $user_id);
        $statement->bindParam(':id_problema', $id_propus);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode(['nota' => $result['nota'],
            'id' => $result['id'],
        'data'=>$result['data_notare']]);
        } else {
            echo json_encode(['nota' => null]);
        }
        }
       else {
        $query="SELECT s.id AS id,n.nota AS nota, n.data_notare AS data_notare from solutii s 
        JOIN note n on n.id_solutie = s.id
        WHERE s.id_elev = :id_elev AND s.id_problema = :id_problema
        ORDER BY n.data_notare DESC LIMIT 1";
        $statement = $db->prepare($query);
        $statement->bindParam(':id_elev', $user_id);
        $statement->bindParam(':id_problema', $id_problema);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode(['nota' => $result['nota'],
            'id' => $result['id'],
        'data'=>$result['data_notare']]);
        } else {
            echo json_encode(['nota' => null]);
        }

    }
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

