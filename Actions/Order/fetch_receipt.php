<?php
header('Content-Type: application/json');
require_once '../../Database/runQuery.php';
$sql = "SELECT ot.*,p.product_name, c.category_name, pmt.payment_method, CONCAT(u.first_name, ' ', u.last_name) AS full_name, u.email, CONCAT(addr.address_line, ', ', addr.city, ', ', addr.province, ' ', addr.postal_code) AS address_line, addr.phone, o.order_date, o.order_status FROM orders o JOIN order_items ot ON o.order_id = ot.order_id JOIN products p ON ot.product_id = p.product_id JOIN categories c ON p.category_id = c.category_id JOIN payments pmt ON o.order_id = pmt.order_id JOIN users u ON o.user_id = u.user_id JOIN addresses addr ON o.address_id = addr.address_id WHERE o.order_id = ?";
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
    'customer' => [
        'full_name' => $order[0]['full_name'],
        'email' => $order[0]['email'],
        'contact_number' => $order[0]['phone'],
        'address_line' => $order[0]['address_line']
    ],
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


