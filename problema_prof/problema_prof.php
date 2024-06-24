<?php
include '../templates/config.php';

try {
    if (isset($_GET['id'])&&isset($_GET['clasa'])) {
        $titlu = htmlspecialchars($_GET['id']);
        $nume_clasa = htmlspecialchars($_GET['clasa']);

        // Fetch the problem details
        $query = "SELECT * FROM probleme WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->execute(array(':id' => $titlu));
        $problema = $statement->fetch(PDO::FETCH_ASSOC);

        // Fetch student solutions for the given problem and class
        $query_studenti = "
            SELECT users.id, users.nume, users.prenume, solutii.id AS id_rezolvare
            FROM users
            JOIN clasa_elevi ON users.id = clasa_elevi.id_elev
            JOIN solutii ON users.id = solutii.id_elev
            WHERE clasa_elevi.nume_clasa = :nume_clasa
            AND solutii.id_problema = :id_problema
            AND solutii.id IN (
                SELECT MAX(sol.id)
                FROM solutii sol
                WHERE sol.id_problema = :id_problema
                GROUP BY sol.id_elev
            )
        ";
        $statement_studenti = $db->prepare($query_studenti);
        $statement_studenti->execute(array(':nume_clasa' => $nume_clasa, ':id_problema' => $titlu));
        $rezolvari = $statement_studenti->fetchAll(PDO::FETCH_ASSOC);

        
    
    } else {
        echo json_encode(['error' => 'No problem title or class specified']);
        exit();
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection error: ' . $e->getMessage()]);
    exit();
}

$response = [
    'problema' => $problema,
    'rezolvari' => $rezolvari
];

echo json_encode($response);
?>
