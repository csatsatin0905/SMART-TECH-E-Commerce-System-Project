<?php
header('Content-Type: application/json');
session_start();
require_once '../Database/runQuery.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notificationId = $_POST['notification_id'] ?? null;
    if (!$notificationId) {
        echo json_encode(['success' => false, 'error' => 'Notification ID is required.']);
        exit;
    }
    try {
        $sql = "UPDATE notifications SET is_read = 1 WHERE notification_id = :id";
        $params = ['id' => $notificationId];
        runQuery($pdo, $sql, $params);
        echo json_encode(['success' => true, 'message' => 'Notification marked as read.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}