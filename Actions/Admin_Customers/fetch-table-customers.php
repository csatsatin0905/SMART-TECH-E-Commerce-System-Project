<?php
header('Content-Type: application/json');
session_start();
require_once '../../Database/runQuery.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $sql = "SELECT u.user_id, u.created_at, u.is_active, CONCAT(u.first_name,' ',u.last_name) AS full_name, u.email, addr.phone, SUM(CASE WHEN o.order_status = 'delivered' THEN o.total_amount ELSE 0 END) AS total_spent, COUNT(DISTINCT o.order_id) AS total_orders
                FROM users u
                LEFT JOIN addresses addr ON u.current_address_id = addr.address_id
                LEFT JOIN orders o ON u.user_id = o.user_id
                LEFT JOIN order_items oi ON o.order_id = oi.order_id
                WHERE u.role = 'user'
                GROUP BY u.user_id, u.created_at, u.is_active, full_name, u.email, addr.phone
                ORDER BY u.created_at DESC";
        $products = runQuery($pdo, $sql, [], true);

        echo json_encode(['success' => true, 'data' => $products]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}