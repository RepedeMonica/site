<?php
include  '../templates/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['solutie_file'])) {
    $file_name = $_FILES['solutie_file']['name'];
    $file_tmp = $_FILES['solutie_file']['tmp_name'];
    $file_type = $_FILES['solutie_file']['type'];

    $propus = $_POST['propus'];

    if ($file_type == "text/plain") {
        if($propus){
            $solutie_text = file_get_contents($file_tmp);

        $id_elev = $user_id;
        $id_problema = htmlspecialchars($_POST['id_problema']);
        try {
            $query_insert_solution = "INSERT INTO solutii_propuse (id_elev, id_problema, solutie) VALUES (:id_elev, :id_problema, :solutie)";
        $statement_insert_solution = $db->prepare($query_insert_solution);
        $statement_insert_solution->bindParam(':id_elev', $id_elev);
        $statement_insert_solution->bindParam(':id_problema', $id_problema);
        $statement_insert_solution->bindParam(':solutie', $solutie_text);
        $statement_insert_solution->execute();
        } catch (PDOException $e) {
            echo "Eroare la inserarea soluției: " . $e->getMessage();
        }
        }
        else{
        $solutie_text = file_get_contents($file_tmp);

        $id_elev = $user_id;
        $id_problema = htmlspecialchars($_POST['id_problema']);
        try {
            $query_insert_solution = "INSERT INTO solutii (id_elev, id_problema, solutie) VALUES (:id_elev, :id_problema, :solutie)";
            $statement_insert_solution = $db->prepare($query_insert_solution);
            $statement_insert_solution->bindParam(':id_elev', $id_elev);
            $statement_insert_solution->bindParam(':id_problema', $id_problema);
            $statement_insert_solution->bindParam(':solutie', $solutie_text);
            $statement_insert_solution->execute();
        } catch (PDOException $e) {
            echo "Eroare la inserarea soluției: " . $e->getMessage();
        }
    }
    } else {
        echo "Fișierul trebuie să fie de tip text!";
    }
}

?>