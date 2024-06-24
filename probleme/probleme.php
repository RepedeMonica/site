<?php
include '../templates/config.php';

header('Content-Type: application/json');

if (isset($_GET['action']) && $_GET['action'] == 'fetch_probleme') {
    try {
        if (isset($_GET['categorie'])) {
            $categorie = $_GET['categorie'];

            $query = "SELECT titlu, id FROM probleme WHERE categorie LIKE :categorie";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':categorie', '%' . $categorie . '%');
            $stmt->execute();
            $probleme = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($probleme);
        } else {
            echo json_encode([]);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit();
}

try {
    $query_categories = "SELECT categorie FROM probleme";
    $statement_categories = $db->query($query_categories);
    $categories = $statement_categories->fetchAll(PDO::FETCH_ASSOC);

    $allCategories = [];

    foreach ($categories as $row) {
        $categorieList = explode(',', $row['categorie']);
        foreach ($categorieList as $categorie) {
            $categorie = trim($categorie);
            if (!in_array($categorie, $allCategories) && !empty($categorie)) {
                $allCategories[] = $categorie;
            }
        }
    }

    $allCategories = array_unique($allCategories);
    sort($allCategories);

    echo json_encode($allCategories);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
