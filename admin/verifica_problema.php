<?php
include 'initializare.php';

$id = isset($_GET['id']) ? urlencode($_GET['id']) : '';
header("Location: problema_admin.html?id=$id");
exit();
?>
