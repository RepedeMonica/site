<?php
include '../templates/config.php';

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $idProbleme = explode(',', $_POST['id_probleme']);
        $numeClasa = $_POST['nume_clasa'];
        $dataLimita = $_POST['data_limita'];
        $idProf = $user_id;

        try {
            $queryTema = "INSERT INTO teme (nume_clasa, id_prof, data_limita) VALUES (:nume_clasa, :id_prof, :data_limita) RETURNING id";
            $stmtTema = $db->prepare($queryTema);
            $stmtTema->execute([
                ':nume_clasa' => $numeClasa,
                ':id_prof' => $idProf,
                ':data_limita' => $dataLimita
            ]);

            $idTema = $stmtTema->fetch(PDO::FETCH_ASSOC)['id'];

            foreach ($idProbleme as $idProblema) {
                $idProblema = trim($idProblema);
                $queryProblema = "INSERT INTO probleme_tema (id_tema, id_problema) VALUES (:id_tema, :id_problema)";
                $stmtProblema = $db->prepare($queryProblema);
                $stmtProblema->execute([
                    ':id_tema' => $idTema,
                    ':id_problema' => $idProblema
                ]);
            }

            echo "Tema a fost creată cu succes cu ID-ul $idTema!";
        } catch (Exception $e) {
            echo "Eroare la crearea temei: " . $e->getMessage();
        }
        header("Location: ../baraNavigare/clase_prof.html");
        exit();
    }
} catch (PDOException $e) {
    echo "Eroare la conectare: " . $e->getMessage();
}
?>
