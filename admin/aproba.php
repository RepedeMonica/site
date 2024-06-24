<?php
include 'initializare.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nume_problema'], $_POST['cerinta'], $_POST['taguri'], $_POST['dificultate'])) {
        $dbh = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);

        $stmt_max_id = $dbh->query("SELECT MAX(id) AS max_id FROM probleme");
        $max_id_row = $stmt_max_id->fetch(PDO::FETCH_ASSOC);
        $max_id = $max_id_row['max_id'];

        $new_id = $max_id + 1;

        $nume_problema = $_POST['nume_problema'];
        $cerinta = $_POST['cerinta'];
        $taguri = $_POST['taguri'];
        $dificultate = $_POST['dificultate'];
        $old_id=$_POST['id'];

        $stmt_insert_problem = $dbh->prepare("INSERT INTO probleme (id, titlu, tag, categorie, enunt) VALUES (:id, :titlu, :tag, :categorie, :enunt)");

        $stmt_insert_problem->execute(array(
            ':id' => $new_id,
            ':titlu' => $nume_problema,
            ':tag' => $dificultate,
            ':categorie' => $taguri,
            ':enunt' => $cerinta
        ));

        $stmt_delete_proposed_problem = $dbh->prepare("DELETE FROM probleme_propuse WHERE id = :id");
        $stmt_delete_proposed_problem->execute(array(':id' => $old_id));

        header("Location: pagina_administrator.html");
        exit();
    } else {
        echo "Nu s-au furnizat toate datele necesare pentru adăugarea problemei.";
    }
} else {
    echo "Metoda de cerere HTTP incorectă.";
}
?>