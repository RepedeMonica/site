<?php
include '../templates/config.php';

try {
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $id_propus = null;
        $id_problema = null;
        if(isset($_GET['id_propus'])){ $id_propus = $_GET['id_propus'];}
        else $id_problema = $_GET['id_solutie'];

        if($id_propus){
                $query="SELECT s.id,o.enunt AS observatie, o.data_notare AS data_notare from solutii_propuse s 
                JOIN observatii_propuse o on o.id_solutie = s.id
                WHERE s.id_elev = :id_elev AND s.id_problema = :id_problema
                ORDER BY o.data_notare DESC LIMIT 1";
                $statement = $db->prepare($query);
                $statement->bindParam(':id_elev', $user_id);
                $statement->bindParam(':id_problema', $id_propus);
                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
        
                if ($result) {
                    echo json_encode(['observatie' => $result['observatie'],
                'data'=>$result['data_notare']]);
                } else {
                    echo json_encode(['observatie' => null,
                'data'=>null]);
                }
        }
        else{
        $query="SELECT s.id,o.enunt AS observatie, o.data_notare AS data_notare from solutii s 
        JOIN observatii o on o.id_solutie = s.id
        WHERE s.id_elev = :id_elev AND s.id_problema = :id_problema
        ORDER BY o.data_notare DESC LIMIT 1";
        $statement = $db->prepare($query);
        $statement->bindParam(':id_elev', $user_id);
        $statement->bindParam(':id_problema', $id_problema);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode(['observatie' => $result['observatie'],
        'data'=>$result['data_notare']]);
        } else {
            echo json_encode(['observatie' => null,
        'data'=>null]);
        }
    }
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

