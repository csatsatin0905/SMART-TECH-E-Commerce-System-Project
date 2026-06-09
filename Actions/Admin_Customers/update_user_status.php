<?php
header('Content-Type: application/json');
session_start();
require_once '../../Database/runQuery.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $newStatus = isset($_POST['newStatus']) && $_POST['newStatus'] === 'true' ? 1 : 0; //store it as number in the database
        $sql = "UPDATE users SET is_active = :newStatus WHERE user_id = :userId";
        runQuery($pdo, $sql, ['newStatus' => $newStatus, 'userId' => $_POST['userId']]);
        


        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>