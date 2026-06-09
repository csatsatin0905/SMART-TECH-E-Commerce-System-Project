<?php
header('Content-Type: application/json');
session_start();
require_once '../../Database/runQuery.php';

$viewBy = $_POST['group_by'] ?? 'category';
$orderStatus = $_POST['orderStatus'] ?? '';
$timeFilter = $_POST['time_filter'] ?? '';

$viewOptions = [
    'category' => [
        'label' => "dp.category_name",
        'group' => "dp.category_name",
        'order' => "total_sales DESC"
    ],
    'product' => [
        'label' => "dp.product_name",
        'group' => "dp.product_name",
        'order' => "total_sales DESC"
    ]
];

if (!array_key_exists($viewBy, $viewOptions)) {
    $viewBy = 'category';
}

$selected = $viewOptions[$viewBy];

$sql = "
    SELECT
        {$selected['label']} AS product_group,
        SUM(fs.subtotal) AS total_sales,
        SUM(fs.quantity_sold) AS total_quantity,
        COUNT(DISTINCT fs.source_order_id) AS total_orders
    FROM fact_sales fs
    INNER JOIN dim_product dp 
        ON fs.dim_product_id = dp.dim_product_id
    INNER JOIN dim_order_status dos 
        ON fs.dim_status_id = dos.dim_status_id
    INNER JOIN dim_time dt 
        ON fs.dim_time_id = dt.dim_time_id
    WHERE 1 = 1
";

$params = [];

if (!empty($orderStatus)) {
    $sql .= " AND dos.order_status = :order_status";
    $params[':order_status'] = $orderStatus;
}

if ($timeFilter === 'this_year') {
    $sql .= " AND dt.year = YEAR(CURDATE())";
}

if ($timeFilter === 'this_month') {
    $sql .= " AND dt.year = YEAR(CURDATE())";
    $sql .= " AND dt.month = MONTH(CURDATE())";
}

$sql .= "
    GROUP BY {$selected['group']}
    ORDER BY {$selected['order']}
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$productSalesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($productSalesData);