<?php
header('Content-Type: application/json');
session_start();
require_once '../../Database/runQuery.php';


if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['error' => 'Invalid request method']);
    exit;
}

$sql = "SELECT o.*,ot.*,p.product_name, p.image FROM orders o JOIN order_items ot ON o.order_id = ot.order_id JOIN products p ON ot.product_id = p.product_id WHERE o.user_id = ? ORDER BY o.order_date DESC;";
$orders = runQuery($pdo, $sql, [$_SESSION['user_id']], true);
echo json_encode($orders);
