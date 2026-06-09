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

$aggregatedOrders = [];
foreach ($orders as $order) {
    $orderId = $order['order_id'];
    if (!isset($aggregatedOrders[$orderId])) {
        $aggregatedOrders[$orderId] = [
            'order_id' => $orderId,
            'order_date' => $order['order_date'],
            'total_amount' => $order['total_amount'],
            'order_status' => $order['order_status'],
            'items' => ""
        ];
    }
    $aggregatedOrders[$orderId]['items'] .=
        "<div class='order-item'>
            <img src='{$order['image']}' alt='{$order['product_name']}' class='product-img'>
            <div class='item-details'>
                <h4>{$order['product_name']}</h4>
            </div>
        </div>";
}
echo json_encode(array_values($aggregatedOrders));
