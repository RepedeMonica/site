<?php
include '../templates/config.php';

try {
    $id_problema = null;
    $id_problema2 = null;
    
    if (isset($_GET['id_problema'])) {
        $id_problema = $_GET['id_problema'];
    } else if (isset($_GET['id_problema2'])) {
        $id_problema2 = $_GET['id_problema2'];
    } else {
        echo json_encode(["error" => "ID-ul problemei lipsește din parametrii URL-ului."]);
        exit();
    }

    if ($id_problema) {
        $query_select_solution = "SELECT solutie FROM solutii WHERE id_elev = :id_elev AND id_problema = :id_problema ORDER BY data_trimis DESC LIMIT 1";
        $statement_select_solution = $db->prepare($query_select_solution);
        $statement_select_solution->bindParam(':id_elev', $user_id);
        $statement_select_solution->bindParam(':id_problema', $id_problema);
        $statement_select_solution->execute();
        $solutie = $statement_select_solution->fetch(PDO::FETCH_ASSOC);

        if (!$solutie) {
            $solutie = ['solutie' => 'Nu aveți soluții încărcate!'];
        }
    } else if ($id_problema2) {
        $query_select_solution = "SELECT solutie FROM solutii WHERE id = :id_problema";
        $statement_select_solution = $db->prepare($query_select_solution);
        $statement_select_solution->bindParam(':id_problema', $id_problema2);
        $statement_select_solution->execute();
        $solutie = $statement_select_solution->fetch(PDO::FETCH_ASSOC);

        if (!$solutie) {
            $solutie = ['solutie' => 'Nu aveți soluții încărcate!'];
        }
    }

    echo json_encode($solutie);
} catch (PDOException $e) {
    echo json_encode(['error' => "Eroare la conectare: " . $e->getMessage()]);
}
?>
