<?php
header('Content-Type: application/json');
session_start();
require_once '../Database/runQuery.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $notifications = runQuery($pdo, "SELECT * FROM notifications WHERE role_target = 'admin' ORDER BY created_at DESC", [], true);
        echo json_encode(['success' => true, 'data' => $notifications]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}