<?php
include 'initializare.php';

if (isset($_GET['action']) && $_GET['action'] == 'fetch_probleme') {
    try {
        $query = "SELECT id, titlu FROM probleme_propuse";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $probleme = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($probleme);
        exit();
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}
?>
