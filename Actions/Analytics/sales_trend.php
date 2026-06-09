<?php
header('Content-Type: application/json');
session_start();
require_once '../../Database/runQuery.php';

$groupBy = $_POST['group_by'] ?? 'month';
$category = $_POST['category'] ?? ''; //
$paymentMethod = $_POST['paymentMethod'] ?? '';
$orderStatus = $_POST['orderStatus'] ?? 'delivered';

$groupOptions = [
    'year' => [
        'label' => "dt.year",
        'group' => "dt.year",
        'order' => "dt.year"
    ],
    'quarter' => [
        'label' => "CONCAT(dt.year, ' Q', dt.quarter)",
        'group' => "dt.year, dt.quarter",
        'order' => "dt.year, dt.quarter"
    ],
    'month' => [
        'label' => "CONCAT(dt.month_name, ' ', dt.year)",
        'group' => "dt.year, dt.month",
        'order' => "dt.year, dt.month"
    ],
    'day' => [
        'label' => "dt.full_date",
        'group' => "dt.full_date",
        'order' => "dt.full_date"
    ]
];

if (!array_key_exists($groupBy, $groupOptions)) {
    $groupBy = 'month';
}

$selected = $groupOptions[$groupBy];

$sql = "
    SELECT 
        {$selected['label']} AS sales_period,
        SUM(fs.subtotal) AS total_sales,
        SUM(fs.quantity_sold) AS total_quantity,
        COUNT(DISTINCT fs.source_order_id) AS total_orders
    FROM fact_sales fs
    INNER JOIN dim_time dt 
        ON fs.dim_time_id = dt.dim_time_id
    INNER JOIN dim_product dp 
        ON fs.dim_product_id = dp.dim_product_id
    INNER JOIN dim_customer dc 
        ON fs.dim_customer_id = dc.dim_customer_id
    INNER JOIN dim_payment dpay 
        ON fs.dim_payment_id = dpay.dim_payment_id
    INNER JOIN dim_order_status dos 
        ON fs.dim_status_id = dos.dim_status_id
    WHERE 1 = 1
";

$params = [];

if (!empty($category)) {
    $sql .= " AND dp.category_name = :category";
    $params[':category'] = $category;
}

if (!empty($paymentMethod)) {
    $sql .= " AND dpay.payment_method = :payment_method";
    $params[':payment_method'] = $paymentMethod;
}

if (!empty($orderStatus)) {
    $sql .= " AND dos.order_status = :order_status";
    $params[':order_status'] = $orderStatus;
}

$sql .= "
    GROUP BY {$selected['group']}
    ORDER BY {$selected['order']}
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$salesTrend = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo (json_encode($salesTrend));