<?php
include '../templates/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_probleme = htmlspecialchars($_POST['cerinta-problema']);
    $nume_problema = htmlspecialchars($_POST['nume-problema']);
    $numeClasa = htmlspecialchars($_POST['nume_clasa1']);
    $dataLimita = htmlspecialchars($_POST['data_limita1']);

    
    $id_prof = $user_id; 

    try {
        $queryTema = "INSERT INTO teme (nume_clasa, id_prof, data_limita) VALUES (:nume_clasa, :id_prof, :data_limita) RETURNING id";
            $stmtTema = $db->prepare($queryTema);
            $stmtTema->execute([
                ':nume_clasa' => $numeClasa,
                ':id_prof' => $id_prof,
                ':data_limita' => $dataLimita
            ]);

            $idTema = $stmtTema->fetch(PDO::FETCH_ASSOC)['id'];


            $queryProblema = "INSERT INTO probleme_tema (id_tema, enunt,nume_problema)
             VALUES (:id_tema, :enunt, :nume_problema)";
            $stmtProblema = $db->prepare($queryProblema);
            $stmtProblema->execute([
                ':id_tema' => $idTema,
                ':enunt' => $id_probleme,
                ':nume_problema' => $nume_problema
            ]);
        
        header("Location: ../baraNavigare/clase_prof.html");
    } catch (PDOException $e) {
        echo "Eroare la inserarea problemei: " . $e->getMessage();
    }
}
?>
