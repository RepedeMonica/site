<?php
include '../templates/config.php';

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $profil = $_POST['profil'];
        $id_cont_elev = $_POST['id_cont_elev'];

        if ($profil == 'CEX') {
            $nume_clasa = $_POST['nume_clasa'] . '-' . $_POST['profil'];
        } else {
            $nume_clasa = $_POST['nume_clasa'] . $_POST['litera_clasa'];
        }

        $elevi = explode(',', $id_cont_elev);

        if (!isset($user_id)) {
            throw new Exception('ID-ul profesorului nu este setat.');
        }

        $query = "SELECT * FROM clase_prof WHERE nume_clasa = :nume_clasa AND profil = :profil AND id_prof = :id_prof";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nume_clasa', $nume_clasa);
        $stmt->bindParam(':profil', $profil);
        $stmt->bindParam(':id_prof', $user_id);
        $stmt->execute();
        $class_exists = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$class_exists) {
            $query = "INSERT INTO clase_prof (nume_clasa, profil, id_prof) VALUES (:nume_clasa, :profil, :id_prof)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':nume_clasa', $nume_clasa);
            $stmt->bindParam(':profil', $profil);
            $stmt->bindParam(':id_prof', $user_id);
            $stmt->execute();
        }

        foreach ($elevi as $id_elev) {
            $id_elev = trim($id_elev);

            $query = "SELECT id FROM users WHERE id = :id AND tip_utilizator = 'elev'";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $id_elev);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $query = "SELECT * FROM clasa_elevi WHERE nume_clasa = :nume_clasa AND id_prof = :id_prof AND id_elev = :id_elev";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':nume_clasa', $nume_clasa);
                $stmt->bindParam(':id_prof', $user_id);
                $stmt->bindParam(':id_elev', $id_elev);
                $stmt->execute();
                $student_exists = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$student_exists) {
                    $query = "INSERT INTO clasa_elevi (nume_clasa, id_prof, id_elev) VALUES (:nume_clasa, :id_prof, :id_elev)";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':nume_clasa', $nume_clasa);
                    $stmt->bindParam(':id_prof', $user_id);
                    $stmt->bindParam(':id_elev', $id_elev);
                    $stmt->execute();
                }
            } else {
                echo "Elevul cu ID-ul $id_elev nu a fost găsit sau nu este de tip 'elev'.<br>";
            }
        }

        header("Location: ../baraNavigare/clase_prof.html");
        exit();
    }
} catch (PDOException $e) {
    echo "Eroare la conectare: " . $e->getMessage();
} catch (Exception $e) {
    echo "Eroare: " . $e->getMessage();
}
?>
