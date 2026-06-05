<?php
require_once 'runQuery.php';
function getDimId($pdo, $sql, $params, $idColumn)
{
    $result = runQuery($pdo, $sql, $params, true);

    if (!empty($result)) {
        return $result[0][$idColumn];
    }

    return null;
}

function runSalesETL($pdo)
{
    try {
        $pdo->beginTransaction();

        $salesData = runQuery($pdo, "
            SELECT 
                oi.order_item_id,
                o.order_id,
                o.order_date,
                o.total_amount,
                o.order_status,

                oi.product_id,
                oi.quantity,
                oi.price,
                oi.subtotal,

                p.product_name,
                p.status AS product_status,
                c.category_name,

                u.user_id,
                CONCAT(u.first_name, ' ', u.last_name) AS full_name,
                u.gender,

                a.city,
                a.province,

                COALESCE(pay.payment_method, 'COD') AS payment_method,
                COALESCE(pay.payment_status, 'pending') AS payment_status

            FROM orders o

            JOIN order_items oi 
                ON o.order_id = oi.order_id

            JOIN products p 
                ON oi.product_id = p.product_id

            JOIN categories c 
                ON p.category_id = c.category_id

            JOIN users u 
                ON o.user_id = u.user_id

            JOIN addresses a 
                ON o.address_id = a.address_id

            LEFT JOIN payments pay 
                ON o.order_id = pay.order_id

            WHERE o.order_status = 'delivered'
        ", [], true);

        $insertedFacts = 0;

        foreach ($salesData as $row) {

            // clean values to avoid null duplicates

            $gender = !empty($row['gender']) ? $row['gender'] : 'Unknown';
            $city = !empty($row['city']) ? $row['city'] : 'Unknown';
            $province = !empty($row['province']) ? $row['province'] : 'Unknown';

            $paymentMethod = !empty($row['payment_method']) ? $row['payment_method'] : 'COD';
            $paymentStatus = !empty($row['payment_status']) ? $row['payment_status'] : 'pending';

            $date = date('Y-m-d', strtotime($row['order_date']));
            $day = date('d', strtotime($row['order_date']));
            $month = date('m', strtotime($row['order_date']));
            $monthName = date('F', strtotime($row['order_date']));
            $quarter = ceil($month / 3);
            $year = date('Y', strtotime($row['order_date']));


            // dim product
            runQuery($pdo, "
                INSERT IGNORE INTO dim_product (
                    source_product_id,
                    product_name,
                    category_name,
                    product_status
                )
                VALUES (?, ?, ?, ?)
            ", [
                $row['product_id'],
                $row['product_name'],
                $row['category_name'],
                $row['product_status']
            ]);

            $dimProductId = getDimId(
                $pdo,
                "
                SELECT dim_product_id 
                FROM dim_product
                WHERE source_product_id = ?
                AND product_name = ?
                AND category_name = ?
                AND product_status = ?
                ",
                [
                    $row['product_id'],
                    $row['product_name'],
                    $row['category_name'],
                    $row['product_status']
                ],
                'dim_product_id'
            );


            // dim customer
            runQuery($pdo, "
                INSERT IGNORE INTO dim_customer (
                    source_user_id,
                    full_name,
                    gender,
                    city,
                    province
                )
                VALUES (?, ?, ?, ?, ?)
            ", [
                $row['user_id'],
                $row['full_name'],
                $gender,
                $city,
                $province
            ]);

            $dimCustomerId = getDimId(
                $pdo,
                "
                SELECT dim_customer_id
                FROM dim_customer
                WHERE source_user_id = ?
                AND full_name = ?
                AND gender = ?
                AND city = ?
                AND province = ?
                ",
                [
                    $row['user_id'],
                    $row['full_name'],
                    $gender,
                    $city,
                    $province
                ],
                'dim_customer_id'
            );


            // dim time

            runQuery($pdo, "
                INSERT IGNORE INTO dim_time (
                    full_date,
                    day,
                    month,
                    month_name,
                    quarter,
                    year
                )
                VALUES (?, ?, ?, ?, ?, ?)
            ", [
                $date,
                $day,
                $month,
                $monthName,
                $quarter,
                $year
            ]);

            $dimTimeId = getDimId(
                $pdo,
                "
                SELECT dim_time_id
                FROM dim_time
                WHERE full_date = ?
                ",
                [$date],
                'dim_time_id'
            );


            // dim payment

            runQuery($pdo, "
                INSERT IGNORE INTO dim_payment (
                    payment_method,
                    payment_status
                )
                VALUES (?, ?)
            ", [
                $paymentMethod,
                $paymentStatus
            ]);

            $dimPaymentId = getDimId(
                $pdo,
                "
                SELECT dim_payment_id
                FROM dim_payment
                WHERE payment_method = ?
                AND payment_status = ?
                ",
                [
                    $paymentMethod,
                    $paymentStatus
                ],
                'dim_payment_id'
            );


            // dim order status

            runQuery($pdo, "
                INSERT IGNORE INTO dim_order_status (
                    order_status
                )
                VALUES (?)
            ", [
                $row['order_status']
            ]);

            $dimStatusId = getDimId(
                $pdo,
                "
                SELECT dim_status_id
                FROM dim_order_status
                WHERE order_status = ?
                ",
                [
                    $row['order_status']
                ],
                'dim_status_id'
            );



            // fact sales

            $stmt = runQuery($pdo, "
                INSERT IGNORE INTO fact_sales (
                    dim_product_id,
                    dim_customer_id,
                    dim_time_id,
                    dim_payment_id,
                    dim_status_id,

                    source_order_id,
                    source_order_item_id,
                    source_product_id,
                    source_user_id,

                    quantity_sold,
                    unit_price,
                    subtotal,
                    total_amount
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ", [
                $dimProductId,
                $dimCustomerId,
                $dimTimeId,
                $dimPaymentId,
                $dimStatusId,

                $row['order_id'],
                $row['order_item_id'],
                $row['product_id'],
                $row['user_id'],

                $row['quantity'],
                $row['price'],
                $row['subtotal'],
                $row['total_amount']
            ]);

            if ($stmt->rowCount() > 0) {
                $insertedFacts++;
            }
        }

        $pdo->commit();

        echo "ETL completed successfully. New fact records added: " . $insertedFacts;

    } catch (Exception $e) {
        $pdo->rollBack();
        die("ETL error: " . $e->getMessage());
    }
}

// Run the ETL anytime
runSalesETL($pdo);

?>