<?php
include '../templates/config.php';

try {
    $id_solutie = null;
    $id_propus = null;
    if (isset($_GET['id'])) {$id_solutie = $_GET['id'];}
    else if (isset($_GET['idPropus'])) {$id_propus = $_GET['idPropus'];}  
    else {
        echo json_encode(["error" => "ID-ul problemei lipsește din parametrii URL-ului."]);
        exit();
    }

    if($id_solutie){
    $query_select_solution = "SELECT solutie FROM solutii WHERE id = :id_problema";
    $statement_select_solution = $db->prepare($query_select_solution);
    $statement_select_solution->bindParam(':id_problema', $id_solutie);
    $statement_select_solution->execute();
    $solutie = $statement_select_solution->fetch(PDO::FETCH_ASSOC);

    if (!$solutie) {
        $solutie = ['solutie' => 'Nu aveți soluții încărcate!'];
    }

    echo json_encode($solutie);
}
else if($id_propus){
    $query_select_solution = "SELECT solutie FROM solutii_propuse WHERE id = :id_problema";
    $statement_select_solution = $db->prepare($query_select_solution);
    $statement_select_solution->bindParam(':id_problema', $id_propus);
    $statement_select_solution->execute();
    $solutie = $statement_select_solution->fetch(PDO::FETCH_ASSOC);

    if (!$solutie) {
        $solutie = ['solutie' => 'Nu aveți soluții încărcate!'];
    }

    echo json_encode($solutie);
}
} catch (PDOException $e) {
    echo json_encode(['error' => "Eroare la conectare: " . $e->getMessage()]);
}
?>
