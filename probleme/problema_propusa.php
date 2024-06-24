<?php
include  '../templates/config.php';

try {
    $username = isset($username) ? $username : null;
    if ($username) {
        $query_user_id = "SELECT id FROM users WHERE username = :username";
        $statement_user_id = $db->prepare($query_user_id);
        $statement_user_id->execute(array(':username' => $username));
        $result_user_id = $statement_user_id->fetch(PDO::FETCH_ASSOC);
        $user_id = $result_user_id['id'];
    }
    if (isset($_GET['id'])) {
        $titlu = htmlspecialchars($_GET['id']);
        $query = "SELECT * FROM probleme_tema WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->execute(array(':id' => $titlu));
        $problema = $statement->fetch(PDO::FETCH_ASSOC);
    } else {
        echo json_encode(['error' => 'No problem title specified']);
        exit();
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection error: ' . $e->getMessage()]);
    exit();
}

$response = [
    'username' => $username,
    'problema' => $problema,
    'tip_utilizator' => $tip_utilizator
];

echo json_encode($response);

?>