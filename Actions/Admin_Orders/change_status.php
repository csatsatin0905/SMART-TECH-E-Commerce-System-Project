<?php
header('Content-Type: application/json');
session_start();
require_once '../../Database/runQuery.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $result = runQuery($pdo, "UPDATE orders SET order_status = ? WHERE order_id = ?", [$_POST['status'], $_POST['order_id']]);

        //notify user
        $result = runQuery($pdo, "SELECT user_id FROM orders WHERE order_id = ?", [$_POST['order_id']]);
        $user_id = $result->fetchColumn();
        $message = "Your order #" . $_POST['order_id'] . " status has been updated to " . $_POST['status'] . ".";
        runQuery($pdo, "INSERT INTO notifications (user_id, message,title,type, role_target) VALUES (?, ?, ?, ?, ?)", [$user_id, $message, "Order Status Updated", "order", "user"]);

        echo json_encode(['success' => true, 'message' => 'Order status updated successfully.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}