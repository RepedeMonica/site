<?php
include '../templates/config.php';

$title = "Acasă";

$data = [
    'title' => $title,
    'username' => $username
];

header('Content-Type: application/json');
echo json_encode($data);
?>
