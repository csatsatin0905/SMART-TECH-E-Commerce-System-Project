<?php
header('Content-Type: application/json');
session_start();
require_once '../../Database/runQuery.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $products = runQuery($pdo, "SELECT o.*, COUNT(oi.product_id) as item_count, CONCAT(u.first_name, ' ', u.last_name) as customer, u.email as email, o.order_status as status FROM orders o LEFT JOIN order_items oi ON o.order_id = oi.order_id LEFT JOIN users u ON o.user_id = u.user_id GROUP BY o.order_id ORDER BY o.order_date DESC", [], true);

        echo json_encode(['success' => true, 'data' => $products]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}