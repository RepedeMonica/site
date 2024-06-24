<?php
include '../templates/config.php';

try {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'fetch_clasa':
                $query_clase = "SELECT nume_clasa, id_prof FROM clasa_elevi WHERE id_elev = :user_id";
                $stmt_clase = $db->prepare($query_clase);
                $stmt_clase->bindParam(':user_id', $user_id);
                $stmt_clase->execute();
                $clase_info = $stmt_clase->fetchAll(PDO::FETCH_ASSOC);

                $response = [];
                foreach ($clase_info as $clasa_info) {
                    $query_profesor = "SELECT nume, prenume FROM users WHERE id = :id_prof";
                    $stmt_profesor = $db->prepare($query_profesor);
                    $stmt_profesor->bindParam(':id_prof', $clasa_info['id_prof']);
                    $stmt_profesor->execute();
                    $profesor_info = $stmt_profesor->fetch(PDO::FETCH_ASSOC);

                    $response[] = array(
                        'clasa' => $clasa_info['nume_clasa'],
                        'profesor' => $profesor_info['nume'] . ' ' . $profesor_info['prenume'],
                        'id_prof' => $clasa_info['id_prof']
                    );
                }

                echo json_encode($response);
                exit();

            case 'fetch_colegi':
                $nume_clasa = $_GET['nume_clasa'];
                $id_prof = $_GET['id_prof'];

                $query_colegi = "SELECT u.nume, u.prenume 
                                FROM users u 
                                JOIN clasa_elevi e ON e.id_elev = u.id 
                                WHERE e.nume_clasa = :nume_clasa AND e.id_prof = :id_prof AND u.id != :user_id";
                $stmt_colegi = $db->prepare($query_colegi);
                $stmt_colegi->bindParam(':nume_clasa', $nume_clasa);
                $stmt_colegi->bindParam(':id_prof', $id_prof);
                $stmt_colegi->bindParam(':user_id', $user_id);
                $stmt_colegi->execute();
                $colegi_info = $stmt_colegi->fetchAll(PDO::FETCH_ASSOC);

                echo json_encode($colegi_info);
                exit();

            case 'fetch_teme':
                $nume_clasa = $_GET['nume_clasa'];
                $id_prof = $_GET['id_prof'];
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
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    exit();
}
?>
