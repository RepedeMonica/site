<?php
include '../templates/config.php';

if (isset($_GET['id_problema']) && isset($_GET['offset'])) {
    $id_problema = $_GET['id_problema'];
    $offset = $_GET['offset'];
    $limit = 3; 

    try {
        $query = "SELECT comentariu,id FROM comentarii WHERE id_problema = :id_problema ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $statement = $db->prepare($query);
        $statement->bindParam(':id_problema', $id_problema, PDO::PARAM_INT);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->bindParam(':offset', $offset, PDO::PARAM_INT);
        $statement->execute();
        $comentarii = $statement->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'comentarii' => $comentarii]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Parametri insuficienți.']);
}
?>
