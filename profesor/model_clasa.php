<?php
include '../templates/config.php';
if (isset($_GET['action'])) {
    try {
        switch ($_GET['action']) {
            case 'fetch_students':
                $query = "SELECT nume, prenume FROM users u 
                          JOIN clasa_elevi e ON e.id_elev = u.id 
                          WHERE nume_clasa = :nume_clasa AND id_prof = :id_prof";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':id_prof', $user_id);
                $stmt->bindParam(':nume_clasa', $_GET['nume_clasa']);
                $stmt->execute();
                $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode($students);
                exit();

            case 'fetch_teme':
                $nume_clasa = $_GET['nume_clasa'];
                $id_prof = $user_id;

                $current_date = date('Y-m-d');

                $query_teme_active = "SELECT t.id, t.nume_clasa, t.data_limita
                                      FROM teme t
                                      WHERE t.nume_clasa = :nume_clasa AND t.id_prof = :id_prof AND t.data_limita >= :current_date";
                $stmt_teme_active = $db->prepare($query_teme_active);
                $stmt_teme_active->bindParam(':nume_clasa', $nume_clasa);
                $stmt_teme_active->bindParam(':id_prof', $id_prof);
                $stmt_teme_active->bindParam(':current_date', $current_date);
                $stmt_teme_active->execute();
                $teme_active = $stmt_teme_active->fetchAll(PDO::FETCH_ASSOC);

                $query_teme_terminate = "SELECT t.id, t.nume_clasa, t.data_limita
                                         FROM teme t
                                         WHERE t.nume_clasa = :nume_clasa AND t.id_prof = :id_prof AND t.data_limita < :current_date";
                $stmt_teme_terminate = $db->prepare($query_teme_terminate);
                $stmt_teme_terminate->bindParam(':nume_clasa', $nume_clasa);
                $stmt_teme_terminate->bindParam(':id_prof', $id_prof);
                $stmt_teme_terminate->bindParam(':current_date', $current_date);
                $stmt_teme_terminate->execute();
                $teme_terminate = $stmt_teme_terminate->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode(array('active' => $teme_active, 'terminate' => $teme_terminate));
                exit();

            case 'fetch_probleme_tema':
                $id_tema = $_GET['id_tema'];

                $query_probleme_tema = "SELECT p.id, p.titlu 
                                        FROM probleme p
                                        JOIN probleme_tema pt ON p.id = pt.id_problema
                                        WHERE pt.id_tema = :id_tema";
                $stmt_probleme_tema = $db->prepare($query_probleme_tema);
                $stmt_probleme_tema->bindParam(':id_tema', $id_tema);
                $stmt_probleme_tema->execute();
                $probleme_tema = $stmt_probleme_tema->fetchAll(PDO::FETCH_ASSOC);

                $query_probleme_tema1 = "SELECT id, nume_problema, enunt                                        
                                        FROM probleme_tema 
                                        WHERE id_tema = :id_tema AND enunt IS NOT NULL";
                $stmt_probleme_tema1 = $db->prepare($query_probleme_tema1);
                $stmt_probleme_tema1->bindParam(':id_tema', $id_tema);
                $stmt_probleme_tema1->execute();
                $probleme_tema1 = $stmt_probleme_tema1->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode(array('exista'=>$probleme_tema,'propus'=>$probleme_tema1));
                exit();
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        exit();
    }
}
?>
