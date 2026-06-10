<?php
header('Content-Type: application/json');
session_start();
require_once '../Database/runQuery.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "UPDATE notifications SET is_read = 1 WHERE role_target = 'admin'";
        runQuery($pdo, $sql);
        echo json_encode(['success' => true, 'message' => 'All notifications marked as read.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}