<?php

try {
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    $pdo->exec("TRUNCATE TABLE fact_sales");
    $pdo->exec("TRUNCATE TABLE dim_product");
    $pdo->exec("TRUNCATE TABLE dim_customer");
    $pdo->exec("TRUNCATE TABLE dim_time");
    $pdo->exec("TRUNCATE TABLE dim_payment");
    $pdo->exec("TRUNCATE TABLE dim_order_status");

    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    $pdo->exec("
        INSERT INTO dim_product (
            source_product_id,
            product_name,
            category_name,
            product_status
        )
        SELECT
            p.product_id,
            p.product_name,
            c.category_name,
            p.status
        FROM products p
        INNER JOIN categories c
            ON p.category_id = c.category_id
    ");

    $pdo->exec("
        INSERT INTO dim_customer (
            source_user_id,
            full_name,
            gender,
            city,
            province
        )
        SELECT DISTINCT
            u.user_id,
            CONCAT(u.first_name, ' ', u.last_name) AS full_name,
            NULL AS gender,
            a.city,
            a.province
        FROM orders o
        INNER JOIN users u
            ON o.user_id = u.user_id
        INNER JOIN addresses a
            ON o.address_id = a.address_id
    ");

    $pdo->exec("
        INSERT INTO dim_time (
            full_date,
            day,
            month,
            month_name,
            quarter,
            year
        )
        SELECT DISTINCT
            DATE(order_date) AS full_date,
            DAY(order_date) AS day,
            MONTH(order_date) AS month,
            MONTHNAME(order_date) AS month_name,
            QUARTER(order_date) AS quarter,
            YEAR(order_date) AS year
        FROM orders
    ");

    $pdo->exec("
        INSERT INTO dim_payment (
            payment_method,
            payment_status
        )
        SELECT DISTINCT
            payment_method,
            payment_status
        FROM payments
    ");

    $pdo->exec("
        INSERT INTO dim_order_status (
            order_status
        )
        SELECT DISTINCT
            order_status
        FROM orders
    ");

    $pdo->exec("
        INSERT INTO fact_sales (
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
        SELECT
            dp.dim_product_id,
            dc.dim_customer_id,
            dt.dim_time_id,
            dpay.dim_payment_id,
            ds.dim_status_id,

            o.order_id,
            oi.order_item_id,
            p.product_id,
            u.user_id,

            oi.quantity,
            oi.price,
            oi.subtotal,
            o.total_amount
        FROM order_items oi

        INNER JOIN orders o
            ON oi.order_id = o.order_id

        INNER JOIN products p
            ON oi.product_id = p.product_id

        INNER JOIN categories c
            ON p.category_id = c.category_id

        INNER JOIN users u
            ON o.user_id = u.user_id

        INNER JOIN addresses a
            ON o.address_id = a.address_id

        INNER JOIN payments pay
            ON o.order_id = pay.order_id

        INNER JOIN dim_product dp
            ON dp.source_product_id = p.product_id
           AND dp.product_name = p.product_name
           AND dp.category_name = c.category_name
           AND dp.product_status = p.status

        INNER JOIN dim_customer dc
            ON dc.source_user_id = u.user_id
           AND dc.full_name = CONCAT(u.first_name, ' ', u.last_name)
           AND dc.gender IS NULL
           AND dc.city = a.city
           AND dc.province = a.province

        INNER JOIN dim_time dt
            ON dt.full_date = DATE(o.order_date)

        INNER JOIN dim_payment dpay
            ON dpay.payment_method = pay.payment_method
           AND dpay.payment_status = pay.payment_status

        INNER JOIN dim_order_status ds
            ON ds.order_status = o.order_status
    ");

    // echo "<h3>Simple ETL completed successfully.</h3>";
    // echo "OLAP tables were cleared and rebuilt from OLTP data.";

} catch (Exception $e) {
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    // echo "<h3>ETL failed.</h3>";
    // echo "Error: " . $e->getMessage();
}

?>