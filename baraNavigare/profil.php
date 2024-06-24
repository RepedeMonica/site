<?php
include '../templates/config.php';

try {
    $query = "SELECT username, nume, prenume, email, clasa, tip_utilizator, id FROM users WHERE username = :username";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $query_probleme_rezolvate = "SELECT DISTINCT p.titlu, p.id FROM solutii s JOIN probleme p ON s.id_problema = p.id WHERE s.id_elev = :user_id";
    $stmt_probleme_rezolvate = $db->prepare($query_probleme_rezolvate);
    $stmt_probleme_rezolvate->bindParam(':user_id', $user_id);
    $stmt_probleme_rezolvate->execute();
    $probleme_rezolvate = $stmt_probleme_rezolvate->fetchAll(PDO::FETCH_ASSOC);

    $query_probleme_incepute = "SELECT COALESCE(COUNT(*), 0) as numar_probleme_incepute FROM solutii WHERE id_elev = :user_id";
    $stmt_probleme_incepute = $db->prepare($query_probleme_incepute);
    $stmt_probleme_incepute->bindParam(':user_id', $user_id);
    $stmt_probleme_incepute->execute();
    $probleme_incepute = $stmt_probleme_incepute->fetch(PDO::FETCH_ASSOC);

    
    $query_probleme_corecte = "SELECT COALESCE(COUNT(*), 0) as numar_probleme_corecte FROM solutii 
    JOIN note on note.id_solutie = solutii.id
    WHERE solutii.id_elev = :user_id AND note.nota>=8";
    $stmt_probleme_corecte = $db->prepare($query_probleme_corecte);
    $stmt_probleme_corecte->bindParam(':user_id', $user_id);
    $stmt_probleme_corecte->execute();
    $probleme_corecte = $stmt_probleme_corecte->fetch(PDO::FETCH_ASSOC);
    

    $query_problema_random = "SELECT p.titlu, p.id FROM probleme p LEFT JOIN solutii s ON p.id = s.id_problema AND s.id_elev = :user_id WHERE s.id_problema IS NULL ORDER BY RANDOM() LIMIT 1";
    $stmt_problema_random = $db->prepare($query_problema_random);
    $stmt_problema_random->bindParam(':user_id', $user_id);
    $stmt_problema_random->execute();
    $problema_random = $stmt_problema_random->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode([
            'user' => $user,
            'probleme_rezolvate' => $probleme_rezolvate,
            'numar_probleme_incepute' => $probleme_incepute['numar_probleme_incepute'],
            'numar_probleme_corecte' => $probleme_corecte['numar_probleme_corecte'],
            'problema_random' => $problema_random
        ]);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
exit();

?>
