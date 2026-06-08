<?php
header('Content-Type: application/json');
require_once '../../Database/runQuery.php';
$sql = "SELECT o.*,ot.*,p.product_name, c.category_name, pmt.payment_method FROM orders o JOIN order_items ot ON o.order_id = ot.order_id JOIN products p ON ot.product_id = p.product_id JOIN categories c ON p.category_id = c.category_id JOIN payments pmt ON o.order_id = pmt.order_id WHERE o.order_id = ?";
$orderId = $_GET['order_id'] ?? null;
if (!$orderId) {
    echo json_encode(['error' => 'Order ID is required']);
    exit;
}
$order = runQuery($pdo, $sql, [$orderId], true);
if (!$order) {
    echo json_encode(['error' => 'Order not found']);
    exit;
}

$order = [
    'order_id' => $order[0]['order_id'],
    'order_date' => $order[0]['order_date'],
    'payment_method' => $order[0]['payment_method'],
    'status' => $order[0]['order_status'],
    'products' => array_map(function ($item) {
        return [
            'product_name' => $item['product_name'],
            'category_name' => $item['category_name'],
            'quantity' => $item['quantity'],
            'price' => $item['price']
        ];
    }, $order)
];
echo json_encode(['success' => true, 'data' => $order]);


