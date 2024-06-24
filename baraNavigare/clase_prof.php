<?php
include '../templates/config.php';

if (isset($_GET['action']) && $_GET['action'] == 'fetch_classes') {
    try {
        $query = "SELECT nume_clasa, profil FROM clase_prof WHERE id_prof = :id_prof";
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
}

$title = "Clasele mele profesor";
?>