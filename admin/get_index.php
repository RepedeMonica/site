<?php
include 'initializare.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

try {
    $query = "
        SELECT row_number FROM (
            SELECT id, ROW_NUMBER() OVER (ORDER BY id) - 1 AS row_number
            FROM probleme_propuse
        ) AS numbered_problems
        WHERE id = :id
    ";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode(['index' => $result['row_number']]);
    } else {
        echo json_encode(['error' => 'ID not found']);
    }
    exit();
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit();
}
?>
