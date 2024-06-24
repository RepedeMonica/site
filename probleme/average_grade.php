<?php
include '../templates/config.php';

$user_id = $user_data->user_id; 

try {
    $query_average_grade = "
        SELECT COALESCE(AVG(note.nota), 0) as media_solutii
        FROM solutii 
        JOIN note on note.id_solutie = solutii.id
        WHERE solutii.id_elev = :user_id";
    $statement_average_grade = $db->prepare($query_average_grade);
    $statement_average_grade->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $statement_average_grade->execute();
    $result = $statement_average_grade->fetch(PDO::FETCH_ASSOC);
    $media_solutii = $result['media_solutii'];

    echo json_encode([
        'media_solutii' => $media_solutii
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
