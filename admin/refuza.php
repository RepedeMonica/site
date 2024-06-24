<?php
include 'initializare.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id'])) {
        $dbh = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);

        $id_problema = $_POST['id'];
        $stmt_delete_proposed_problem = $dbh->prepare("DELETE FROM probleme_propuse WHERE id = :id");
        $stmt_delete_proposed_problem->execute(array(':id' => $id_problema));

        header("Location: pagina_administrator.html");
        exit();
    } else {
        echo "Nu s-au furnizat toate datele necesare pentru ștergerea problemei.";
    }
} else {
    echo "Metoda de cerere HTTP incorectă.";
}
?>
