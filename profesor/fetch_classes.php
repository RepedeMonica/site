<?php
include '../templates/config.php';

try {

    $query = "SELECT nume_clasa FROM clase_prof WHERE id_prof = :id_prof";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id_prof', $user_id);
    $stmt->execute();
    $clase = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($clase);
    exit();
} catch (PDOException $e) {
    echo json_encode([]);
    exit();
}
?>
