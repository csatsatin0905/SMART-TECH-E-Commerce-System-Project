<?php
header('Content-Type: application/json');
session_start();
require_once '../Database/runQuery.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = :user_id";
        $params = ['user_id' => $_SESSION['user_id']];
        runQuery($pdo, $sql, $params);
        echo json_encode(['success' => true, 'message' => 'All notifications marked as read.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}