<?php
include '../templates/config.php';

$id_problema = $_GET['id_problema'];

try {
    $query_total = "SELECT COUNT(DISTINCT id_elev) as total 
    FROM solutii WHERE id_problema = :id_problema";
    $statement_total = $db->prepare($query_total);
    $statement_total->bindParam(':id_problema', $id_problema);
    $statement_total->execute();
    $result_total = $statement_total->fetch(PDO::FETCH_ASSOC);
    $total_elevi = $result_total['total'];

    $query_corect = "SELECT COUNT(DISTINCT solutii.id_elev) as corect
                     FROM solutii
                     JOIN note ON solutii.id = note.id_solutie
                     WHERE solutii.id_problema = :id_problema AND note.nota >= 8";
    $statement_corect = $db->prepare($query_corect);
    $statement_corect->bindParam(':id_problema', $id_problema);
    $statement_corect->execute();
    $result_corect = $statement_corect->fetch(PDO::FETCH_ASSOC);
    $total_corect = $result_corect['corect'];

    $query_media = "SELECT AVG(nota) as media FROM note
                    JOIN solutii ON note.id_solutie = solutii.id
                    WHERE solutii.id_problema = :id_problema";
    $statement_media = $db->prepare($query_media);
    $statement_media->bindParam(':id_problema', $id_problema);
    $statement_media->execute();
    $result_media = $statement_media->fetch(PDO::FETCH_ASSOC);
    $media_note = round($result_media['media'], 2);

    $query_mediar = "SELECT AVG(rating) as media FROM 
                    ratings
                    WHERE id_problema = :id_problema";
    $statement_mediar = $db->prepare($query_mediar);
    $statement_mediar->bindParam(':id_problema', $id_problema);
    $statement_mediar->execute();
    $result_mediar = $statement_mediar->fetch(PDO::FETCH_ASSOC);
    $media_rating = round($result_mediar['media'], 2);

    $response = [
        'total_elevi' => $total_elevi,
        'total_corect' => $total_corect,
        'media_note' => $media_note,
        'media_rating' => $media_rating
    ];

    echo json_encode($response);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
