<?php
header('Content-Type: application/json');
session_start();
require_once '../../Database/runQuery.php';

$viewBy = $_POST['group_by'] ?? 'province';

$category = $_POST['category'] ?? '';
$orderStatus = $_POST['order_status'] ?? '';
$paymentMethod = $_POST['payment_method'] ?? '';

$viewOptions = [
    'province' => [
        'label' => "COALESCE(dc.province, 'Unknown')",
        'group' => "dc.province",
        'order' => "total_sales DESC"
    ],
    'city' => [
        'label' => "COALESCE(dc.city, 'Unknown')",
        'group' => "dc.city",
        'order' => "total_sales DESC"
    ]
];

if (!array_key_exists($viewBy, $viewOptions)) {
    $viewBy = 'province';
}

$selected = $viewOptions[$viewBy];

$sql = "
    SELECT
        {$selected['label']} AS location_group,
        SUM(fs.subtotal) AS total_sales,
        SUM(fs.quantity_sold) AS total_quantity,
        COUNT(DISTINCT fs.source_order_id) AS total_orders
    FROM fact_sales fs
    INNER JOIN dim_customer dc 
        ON fs.dim_customer_id = dc.dim_customer_id
    INNER JOIN dim_product dp 
        ON fs.dim_product_id = dp.dim_product_id
    INNER JOIN dim_order_status dos 
        ON fs.dim_status_id = dos.dim_status_id
    INNER JOIN dim_payment dpay 
        ON fs.dim_payment_id = dpay.dim_payment_id
    WHERE 1 = 1
";

$params = [];

if (!empty($category)) {
    $sql .= " AND dp.category_name = :category";
    $params[':category'] = $category;
}

if (!empty($orderStatus)) {
    $sql .= " AND dos.order_status = :order_status";
    $params[':order_status'] = $orderStatus;
}

if (!empty($paymentMethod)) {
    $sql .= " AND dpay.payment_method = :payment_method";
    $params[':payment_method'] = $paymentMethod;
}

$sql .= "
    GROUP BY {$selected['group']}
    ORDER BY {$selected['order']}
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$locationSalesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($locationSalesData);