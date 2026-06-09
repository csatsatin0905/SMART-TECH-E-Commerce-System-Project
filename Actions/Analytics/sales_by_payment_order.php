<?php
header('Content-Type: application/json');
session_start();
require_once '../../Database/runQuery.php';

$viewBy = $_POST['group_by'] ?? 'payment_method';
$valueBy = $_POST['value_by'] ?? 'sales_amount';

$category = $_POST['category'] ?? '';
$timeFilter = $_POST['time_filter'] ?? '';

$viewOptions = [
    'payment_method' => [
        'label' => "dpay.payment_method",
        'group' => "dpay.payment_method",
        'order' => "chart_value DESC"
    ],
    'payment_status' => [
        'label' => "dpay.payment_status",
        'group' => "dpay.payment_status",
        'order' => "chart_value DESC"
    ],
    'order_status' => [
        'label' => "dos.order_status",
        'group' => "dos.order_status",
        'order' => "chart_value DESC"
    ]
];

$valueOptions = [
    'sales_amount' => "SUM(fs.subtotal)",
    'order_count' => "COUNT(DISTINCT fs.source_order_id)",
    'quantity_sold' => "SUM(fs.quantity_sold)"
];

if (!array_key_exists($viewBy, $viewOptions)) {
    $viewBy = 'payment_method';
}

if (!array_key_exists($valueBy, $valueOptions)) {
    $valueBy = 'sales_amount';
}

$selectedView = $viewOptions[$viewBy];
$selectedValue = $valueOptions[$valueBy];

$sql = "
    SELECT
        {$selectedView['label']} AS chart_label,
        {$selectedValue} AS chart_value
    FROM fact_sales fs
    INNER JOIN dim_payment dpay 
        ON fs.dim_payment_id = dpay.dim_payment_id
    INNER JOIN dim_order_status dos 
        ON fs.dim_status_id = dos.dim_status_id
    INNER JOIN dim_product dp 
        ON fs.dim_product_id = dp.dim_product_id
    INNER JOIN dim_time dt 
        ON fs.dim_time_id = dt.dim_time_id
    WHERE 1 = 1
";

$params = [];

if (!empty($category)) {
    $sql .= " AND dp.category_name = :category";
    $params[':category'] = $category;
}

if ($timeFilter === 'this_year') {
    $sql .= " AND dt.year = YEAR(CURDATE())";
}

if ($timeFilter === 'this_month') {
    $sql .= " AND dt.year = YEAR(CURDATE())";
    $sql .= " AND dt.month = MONTH(CURDATE())";
}

$sql .= "
    GROUP BY {$selectedView['group']}
    ORDER BY {$selectedView['order']}
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$paymentStatusData = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($paymentStatusData);