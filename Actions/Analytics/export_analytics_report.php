<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../Database/runQuery.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

try {
    if (!isset($pdo)) {
        throw new Exception('Database connection not found.');
    }

    $spreadsheet = new Spreadsheet();

    $spreadsheet->getProperties()
        ->setCreator('Smart Tech Admin')
        ->setLastModifiedBy('Smart Tech Admin')
        ->setTitle('Smart Tech Analytics Report')
        ->setSubject('E-Commerce OLAP Analytics Report')
        ->setDescription('Generated Excel report for Smart Tech e-commerce analytics.')
        ->setKeywords('smart tech analytics report sales olap')
        ->setCategory('Analytics Report');

    $generatedAt = date('F d, Y h:i A');

    function fetchRows(PDO $pdo, string $sql, array $params = []): array
    {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function fetchOne(PDO $pdo, string $sql, array $params = []): array
    {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: [];
    }

    function moneyFormat(): string
    {
        return '₱#,##0.00';
    }

    function numberFormat(): string
    {
        return '#,##0';
    }

    function decimalFormat(): string
    {
        return '#,##0.00';
    }

    function cleanSheetTitle(string $title): string
    {
        $title = preg_replace('/[\\\\\\/\\?\\*\\[\\]\\:]/', '-', $title);
        return substr($title, 0, 31);
    }

    function setDefaultSheetStyle(Worksheet $sheet): void
    {
        $sheet->getDefaultRowDimension()->setRowHeight(22);

        $sheet->getStyle($sheet->calculateWorksheetDimension())
            ->getFont()
            ->setName('Calibri')
            ->setSize(11);
    }

    function addReportHeader(
        Worksheet $sheet,
        string $title,
        string $subtitle,
        int $totalColumns = 6
    ): int {
        $lastColumn = Coordinate::stringFromColumnIndex($totalColumns);

        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->setCellValue('A1', $title);
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 18,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '4E0B99'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(32);

        $sheet->mergeCells("A2:{$lastColumn}2");
        $sheet->setCellValue('A2', $subtitle);
        $sheet->getStyle('A2')->applyFromArray([
            'font' => [
                'italic' => true,
                'size' => 10,
                'color' => ['rgb' => '4B5563'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        return 4;
    }

    function writeTable(
        Worksheet $sheet,
        int $startRow,
        array $columns,
        array $rows,
        bool $withAutoFilter = true
    ): int {
        $headerRow = $startRow;
        $colIndex = 1;

        foreach ($columns as $column) {
            $cell = Coordinate::stringFromColumnIndex($colIndex) . $headerRow;
            $sheet->setCellValue($cell, $column['label']);
            $colIndex++;
        }

        $lastColumn = Coordinate::stringFromColumnIndex(count($columns));

        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$headerRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => '2563EB'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'],
                ],
            ],
        ]);

        $sheet->getRowDimension($headerRow)->setRowHeight(24);

        $rowIndex = $headerRow + 1;

        if (empty($rows)) {
            $sheet->setCellValue("A{$rowIndex}", 'No data available');
            $sheet->mergeCells("A{$rowIndex}:{$lastColumn}{$rowIndex}");
            $sheet->getStyle("A{$rowIndex}")->getFont()->setItalic(true);
            return $rowIndex + 2;
        }

        foreach ($rows as $row) {
            $colIndex = 1;

            foreach ($columns as $column) {
                $key = $column['key'];
                $value = $row[$key] ?? '';

                $cell = Coordinate::stringFromColumnIndex($colIndex) . $rowIndex;
                $sheet->setCellValue($cell, $value);

                $colIndex++;
            }

            $rowIndex++;
        }

        $lastRow = $rowIndex - 1;

        $sheet->getStyle("A{$headerRow}:{$lastColumn}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E5E7EB'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        for ($i = 1; $i <= count($columns); $i++) {
            $columnLetter = Coordinate::stringFromColumnIndex($i);
            $type = $columns[$i - 1]['type'] ?? 'text';

            $dataRange = "{$columnLetter}" . ($headerRow + 1) . ":{$columnLetter}{$lastRow}";

            if ($type === 'currency') {
                $sheet->getStyle($dataRange)->getNumberFormat()->setFormatCode(moneyFormat());
            }

            if ($type === 'number') {
                $sheet->getStyle($dataRange)->getNumberFormat()->setFormatCode(numberFormat());
            }

            if ($type === 'decimal') {
                $sheet->getStyle($dataRange)->getNumberFormat()->setFormatCode(decimalFormat());
            }

            if ($type === 'date') {
                $sheet->getStyle($dataRange)->getNumberFormat()->setFormatCode('yyyy-mm-dd');
            }

            $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
        }

        if ($withAutoFilter) {
            $sheet->setAutoFilter("A{$headerRow}:{$lastColumn}{$lastRow}");
        }

        $sheet->freezePane('A' . ($headerRow + 1));

        return $lastRow + 3;
    }

    function createSheet(Spreadsheet $spreadsheet, string $title, bool $firstSheet = false): Worksheet
    {
        if ($firstSheet) {
            $sheet = $spreadsheet->getActiveSheet();
        } else {
            $sheet = $spreadsheet->createSheet();
        }

        $sheet->setTitle(cleanSheetTitle($title));
        return $sheet;
    }

    //kpi queries


    $totalSales = fetchOne($pdo, "
        SELECT COALESCE(SUM(fs.subtotal), 0) AS value
        FROM fact_sales fs
        INNER JOIN dim_order_status dos 
            ON fs.dim_status_id = dos.dim_status_id
        WHERE dos.order_status = 'delivered'
    ")['value'] ?? 0;

    $totalOrders = fetchOne($pdo, "
        SELECT COUNT(DISTINCT fs.source_order_id) AS value
        FROM fact_sales fs
        INNER JOIN dim_order_status dos 
            ON fs.dim_status_id = dos.dim_status_id
        WHERE dos.order_status = 'delivered'
    ")['value'] ?? 0;

    $totalProductsSold = fetchOne($pdo, "
        SELECT COALESCE(SUM(fs.quantity_sold), 0) AS value
        FROM fact_sales fs
        INNER JOIN dim_order_status dos 
            ON fs.dim_status_id = dos.dim_status_id
        WHERE dos.order_status = 'delivered'
    ")['value'] ?? 0;

    $averageOrderValue = fetchOne($pdo, "
        SELECT 
            COALESCE(
                SUM(fs.subtotal) / NULLIF(COUNT(DISTINCT fs.source_order_id), 0),
                0
            ) AS value
        FROM fact_sales fs
        INNER JOIN dim_order_status dos 
            ON fs.dim_status_id = dos.dim_status_id
        WHERE dos.order_status = 'delivered'
    ")['value'] ?? 0;

    $topSellingProduct = fetchOne($pdo, "
        SELECT 
            dp.product_name,
            SUM(fs.quantity_sold) AS total_quantity_sold
        FROM fact_sales fs
        INNER JOIN dim_product dp 
            ON fs.dim_product_id = dp.dim_product_id
        INNER JOIN dim_order_status dos 
            ON fs.dim_status_id = dos.dim_status_id
        WHERE dos.order_status = 'delivered'
        GROUP BY dp.product_name
        ORDER BY total_quantity_sold DESC
        LIMIT 1
    ");

    $leastSellingProduct = fetchOne($pdo, "
        SELECT 
            dp.product_name,
            SUM(fs.quantity_sold) AS total_quantity_sold
        FROM fact_sales fs
        INNER JOIN dim_product dp 
            ON fs.dim_product_id = dp.dim_product_id
        INNER JOIN dim_order_status dos 
            ON fs.dim_status_id = dos.dim_status_id
        WHERE dos.order_status = 'delivered'
        GROUP BY dp.product_name
        ORDER BY total_quantity_sold ASC
        LIMIT 1
    ");

    $bestSellingCategory = fetchOne($pdo, "
        SELECT 
            dp.category_name,
            SUM(fs.subtotal) AS total_sales
        FROM fact_sales fs
        INNER JOIN dim_product dp 
            ON fs.dim_product_id = dp.dim_product_id
        INNER JOIN dim_order_status dos 
            ON fs.dim_status_id = dos.dim_status_id
        WHERE dos.order_status = 'delivered'
        GROUP BY dp.category_name
        ORDER BY total_sales DESC
        LIMIT 1
    ");

    $mostUsedPaymentMethod = fetchOne($pdo, "
        SELECT 
            dpay.payment_method,
            COUNT(DISTINCT fs.source_order_id) AS total_orders
        FROM fact_sales fs
        INNER JOIN dim_payment dpay 
            ON fs.dim_payment_id = dpay.dim_payment_id
        GROUP BY dpay.payment_method
        ORDER BY total_orders DESC
        LIMIT 1
    ");

    //summary

    $summarySheet = createSheet($spreadsheet, 'Summary Report', true);
    addReportHeader(
        $summarySheet,
        'Smart Tech Analytics Report',
        "Generated on {$generatedAt}",
        4
    );

    $summarySheet->setCellValue('A5', 'Metric');
    $summarySheet->setCellValue('B5', 'Value');

    $summarySheet->getStyle('A5:B5')->applyFromArray([
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF'],
        ],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'color' => ['rgb' => '2563EB'],
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => 'D1D5DB'],
            ],
        ],
    ]);

    $kpiRows = [
        ['Total Sales - Delivered Orders', (float) $totalSales, 'currency'],
        ['Total Orders - Delivered Orders', (int) $totalOrders, 'number'],
        ['Total Products Sold - Delivered Orders', (int) $totalProductsSold, 'number'],
        ['Average Order Value - Delivered Orders', (float) $averageOrderValue, 'currency'],
        ['Top Selling Product', $topSellingProduct['product_name'] ?? 'N/A', 'text'],
        ['Top Selling Product Quantity', $topSellingProduct['total_quantity_sold'] ?? 0, 'number'],
        ['Least Selling Product', $leastSellingProduct['product_name'] ?? 'N/A', 'text'],
        ['Least Selling Product Quantity', $leastSellingProduct['total_quantity_sold'] ?? 0, 'number'],
        ['Best Selling Category', $bestSellingCategory['category_name'] ?? 'N/A', 'text'],
        ['Best Selling Category Sales', $bestSellingCategory['total_sales'] ?? 0, 'currency'],
        ['Most Used Payment Method', $mostUsedPaymentMethod['payment_method'] ?? 'N/A', 'text'],
        ['Most Used Payment Method Orders', $mostUsedPaymentMethod['total_orders'] ?? 0, 'number'],
    ];

    $row = 6;

    foreach ($kpiRows as $kpi) {
        $summarySheet->setCellValue("A{$row}", $kpi[0]);
        $summarySheet->setCellValue("B{$row}", $kpi[1]);

        if ($kpi[2] === 'currency') {
            $summarySheet->getStyle("B{$row}")->getNumberFormat()->setFormatCode(moneyFormat());
        }

        if ($kpi[2] === 'number') {
            $summarySheet->getStyle("B{$row}")->getNumberFormat()->setFormatCode(numberFormat());
        }

        $row++;
    }

    $summarySheet->getStyle("A5:B" . ($row - 1))->applyFromArray([
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN,
                'color' => ['rgb' => 'E5E7EB'],
            ],
        ],
    ]);

    $summarySheet->getColumnDimension('A')->setWidth(42);
    $summarySheet->getColumnDimension('B')->setWidth(32);
    $summarySheet->freezePane('A6');

   //sales trend

    $trendColumns = [
        ['key' => 'sales_period', 'label' => 'Sales Period', 'type' => 'text'],
        ['key' => 'total_sales', 'label' => 'Total Sales', 'type' => 'currency'],
        ['key' => 'total_quantity', 'label' => 'Quantity Sold', 'type' => 'number'],
        ['key' => 'total_orders', 'label' => 'Total Orders', 'type' => 'number'],
    ];

    $trendOptions = [
        'Sales Trend - Year' => [
            'label' => 'dt.year',
            'group' => 'dt.year',
            'order' => 'dt.year',
        ],
        'Sales Trend - Quarter' => [
            'label' => "CONCAT(dt.year, ' Q', dt.quarter)",
            'group' => 'dt.year, dt.quarter',
            'order' => 'dt.year, dt.quarter',
        ],
        'Sales Trend - Month' => [
            'label' => "CONCAT(dt.month_name, ' ', dt.year)",
            'group' => 'dt.year, dt.month',
            'order' => 'dt.year, dt.month',
        ],
        'Sales Trend - Day' => [
            'label' => 'dt.full_date',
            'group' => 'dt.full_date',
            'order' => 'dt.full_date',
        ],
    ];

    foreach ($trendOptions as $sheetTitle => $option) {
        $rows = fetchRows($pdo, "
            SELECT
                {$option['label']} AS sales_period,
                COALESCE(SUM(fs.subtotal), 0) AS total_sales,
                COALESCE(SUM(fs.quantity_sold), 0) AS total_quantity,
                COUNT(DISTINCT fs.source_order_id) AS total_orders
            FROM fact_sales fs
            INNER JOIN dim_time dt 
                ON fs.dim_time_id = dt.dim_time_id
            INNER JOIN dim_order_status dos 
                ON fs.dim_status_id = dos.dim_status_id
            WHERE dos.order_status = 'delivered'
            GROUP BY {$option['group']}
            ORDER BY {$option['order']}
        ");

        $sheet = createSheet($spreadsheet, $sheetTitle);
        $startRow = addReportHeader(
            $sheet,
            $sheetTitle,
            'Sales trend report based on delivered orders',
            4
        );
        writeTable($sheet, $startRow, $trendColumns, $rows);
    }

   

    $categoryRows = fetchRows($pdo, "
        SELECT
            dp.category_name AS category_name,
            COALESCE(SUM(fs.subtotal), 0) AS total_sales,
            COALESCE(SUM(fs.quantity_sold), 0) AS total_quantity,
            COUNT(DISTINCT fs.source_order_id) AS total_orders
        FROM fact_sales fs
        INNER JOIN dim_product dp 
            ON fs.dim_product_id = dp.dim_product_id
        INNER JOIN dim_order_status dos 
            ON fs.dim_status_id = dos.dim_status_id
        WHERE dos.order_status = 'delivered'
        GROUP BY dp.category_name
        ORDER BY total_sales DESC
    ");

    $sheet = createSheet($spreadsheet, 'Sales Per Category');
    $startRow = addReportHeader(
        $sheet,
        'Sales Per Category',
        'Product category sales report based on delivered orders',
        4
    );

    writeTable($sheet, $startRow, [
        ['key' => 'category_name', 'label' => 'Category', 'type' => 'text'],
        ['key' => 'total_sales', 'label' => 'Total Sales', 'type' => 'currency'],
        ['key' => 'total_quantity', 'label' => 'Quantity Sold', 'type' => 'number'],
        ['key' => 'total_orders', 'label' => 'Total Orders', 'type' => 'number'],
    ], $categoryRows);

   

    $productRows = fetchRows($pdo, "
        SELECT
            dp.product_name AS product_name,
            dp.category_name AS category_name,
            COALESCE(SUM(fs.subtotal), 0) AS total_sales,
            COALESCE(SUM(fs.quantity_sold), 0) AS total_quantity,
            COUNT(DISTINCT fs.source_order_id) AS total_orders
        FROM fact_sales fs
        INNER JOIN dim_product dp 
            ON fs.dim_product_id = dp.dim_product_id
        INNER JOIN dim_order_status dos 
            ON fs.dim_status_id = dos.dim_status_id
        WHERE dos.order_status = 'delivered'
        GROUP BY dp.product_name, dp.category_name
        ORDER BY total_sales DESC
    ");

    $sheet = createSheet($spreadsheet, 'Sales Per Product');
    $startRow = addReportHeader(
        $sheet,
        'Sales Per Product',
        'Detailed product sales report based on delivered orders',
        5
    );

    writeTable($sheet, $startRow, [
        ['key' => 'product_name', 'label' => 'Product', 'type' => 'text'],
        ['key' => 'category_name', 'label' => 'Category', 'type' => 'text'],
        ['key' => 'total_sales', 'label' => 'Total Sales', 'type' => 'currency'],
        ['key' => 'total_quantity', 'label' => 'Quantity Sold', 'type' => 'number'],
        ['key' => 'total_orders', 'label' => 'Total Orders', 'type' => 'number'],
    ], $productRows);


    $provinceRows = fetchRows($pdo, "
        SELECT
            COALESCE(dc.province, 'Unknown') AS province,
            COALESCE(SUM(fs.subtotal), 0) AS total_sales,
            COALESCE(SUM(fs.quantity_sold), 0) AS total_quantity,
            COUNT(DISTINCT fs.source_order_id) AS total_orders
        FROM fact_sales fs
        INNER JOIN dim_customer dc 
            ON fs.dim_customer_id = dc.dim_customer_id
        INNER JOIN dim_order_status dos 
            ON fs.dim_status_id = dos.dim_status_id
        WHERE dos.order_status = 'delivered'
        GROUP BY dc.province
        ORDER BY total_sales DESC
    ");

    $sheet = createSheet($spreadsheet, 'Sales Per Province');
    $startRow = addReportHeader(
        $sheet,
        'Sales Per Province',
        'Location sales report grouped by province',
        4
    );

    writeTable($sheet, $startRow, [
        ['key' => 'province', 'label' => 'Province', 'type' => 'text'],
        ['key' => 'total_sales', 'label' => 'Total Sales', 'type' => 'currency'],
        ['key' => 'total_quantity', 'label' => 'Quantity Sold', 'type' => 'number'],
        ['key' => 'total_orders', 'label' => 'Total Orders', 'type' => 'number'],
    ], $provinceRows);


    $cityRows = fetchRows($pdo, "
        SELECT
            COALESCE(dc.province, 'Unknown') AS province,
            COALESCE(dc.city, 'Unknown') AS city,
            COALESCE(SUM(fs.subtotal), 0) AS total_sales,
            COALESCE(SUM(fs.quantity_sold), 0) AS total_quantity,
            COUNT(DISTINCT fs.source_order_id) AS total_orders
        FROM fact_sales fs
        INNER JOIN dim_customer dc 
            ON fs.dim_customer_id = dc.dim_customer_id
        INNER JOIN dim_order_status dos 
            ON fs.dim_status_id = dos.dim_status_id
        WHERE dos.order_status = 'delivered'
        GROUP BY dc.province, dc.city
        ORDER BY total_sales DESC
    ");

    $sheet = createSheet($spreadsheet, 'Sales Per City');
    $startRow = addReportHeader(
        $sheet,
        'Sales Per City',
        'Location sales report grouped by city',
        5
    );

    writeTable($sheet, $startRow, [
        ['key' => 'province', 'label' => 'Province', 'type' => 'text'],
        ['key' => 'city', 'label' => 'City', 'type' => 'text'],
        ['key' => 'total_sales', 'label' => 'Total Sales', 'type' => 'currency'],
        ['key' => 'total_quantity', 'label' => 'Quantity Sold', 'type' => 'number'],
        ['key' => 'total_orders', 'label' => 'Total Orders', 'type' => 'number'],
    ], $cityRows);


    $paymentMethodRows = fetchRows($pdo, "
        SELECT
            dpay.payment_method AS payment_method,
            COALESCE(SUM(fs.subtotal), 0) AS total_sales,
            COALESCE(SUM(fs.quantity_sold), 0) AS total_quantity,
            COUNT(DISTINCT fs.source_order_id) AS total_orders
        FROM fact_sales fs
        INNER JOIN dim_payment dpay 
            ON fs.dim_payment_id = dpay.dim_payment_id
        GROUP BY dpay.payment_method
        ORDER BY total_sales DESC
    ");

    $sheet = createSheet($spreadsheet, 'Payment Method');
    $startRow = addReportHeader(
        $sheet,
        'Payment Method Breakdown',
        'Sales, quantity, and order count grouped by payment method',
        4
    );

    writeTable($sheet, $startRow, [
        ['key' => 'payment_method', 'label' => 'Payment Method', 'type' => 'text'],
        ['key' => 'total_sales', 'label' => 'Total Sales', 'type' => 'currency'],
        ['key' => 'total_quantity', 'label' => 'Quantity Sold', 'type' => 'number'],
        ['key' => 'total_orders', 'label' => 'Total Orders', 'type' => 'number'],
    ], $paymentMethodRows);


    $paymentStatusRows = fetchRows($pdo, "
        SELECT
            dpay.payment_status AS payment_status,
            COALESCE(SUM(fs.subtotal), 0) AS total_sales,
            COALESCE(SUM(fs.quantity_sold), 0) AS total_quantity,
            COUNT(DISTINCT fs.source_order_id) AS total_orders
        FROM fact_sales fs
        INNER JOIN dim_payment dpay 
            ON fs.dim_payment_id = dpay.dim_payment_id
        GROUP BY dpay.payment_status
        ORDER BY total_sales DESC
    ");

    $sheet = createSheet($spreadsheet, 'Payment Status');
    $startRow = addReportHeader(
        $sheet,
        'Payment Status Breakdown',
        'Sales, quantity, and order count grouped by payment status',
        4
    );

    writeTable($sheet, $startRow, [
        ['key' => 'payment_status', 'label' => 'Payment Status', 'type' => 'text'],
        ['key' => 'total_sales', 'label' => 'Total Sales', 'type' => 'currency'],
        ['key' => 'total_quantity', 'label' => 'Quantity Sold', 'type' => 'number'],
        ['key' => 'total_orders', 'label' => 'Total Orders', 'type' => 'number'],
    ], $paymentStatusRows);

    $orderStatusRows = fetchRows($pdo, "
        SELECT
            dos.order_status AS order_status,
            COALESCE(SUM(fs.subtotal), 0) AS total_sales,
            COALESCE(SUM(fs.quantity_sold), 0) AS total_quantity,
            COUNT(DISTINCT fs.source_order_id) AS total_orders
        FROM fact_sales fs
        INNER JOIN dim_order_status dos 
            ON fs.dim_status_id = dos.dim_status_id
        GROUP BY dos.order_status
        ORDER BY total_sales DESC
    ");

    $sheet = createSheet($spreadsheet, 'Order Status');
    $startRow = addReportHeader(
        $sheet,
        'Order Status Breakdown',
        'Sales, quantity, and order count grouped by order status',
        4
    );

    writeTable($sheet, $startRow, [
        ['key' => 'order_status', 'label' => 'Order Status', 'type' => 'text'],
        ['key' => 'total_sales', 'label' => 'Total Sales', 'type' => 'currency'],
        ['key' => 'total_quantity', 'label' => 'Quantity Sold', 'type' => 'number'],
        ['key' => 'total_orders', 'label' => 'Total Orders', 'type' => 'number'],
    ], $orderStatusRows);


    $paymentOrderRows = fetchRows($pdo, "
        SELECT
            dpay.payment_method AS payment_method,
            dpay.payment_status AS payment_status,
            dos.order_status AS order_status,
            COALESCE(SUM(fs.subtotal), 0) AS total_sales,
            COALESCE(SUM(fs.quantity_sold), 0) AS total_quantity,
            COUNT(DISTINCT fs.source_order_id) AS total_orders
        FROM fact_sales fs
        INNER JOIN dim_payment dpay 
            ON fs.dim_payment_id = dpay.dim_payment_id
        INNER JOIN dim_order_status dos 
            ON fs.dim_status_id = dos.dim_status_id
        GROUP BY dpay.payment_method, dpay.payment_status, dos.order_status
        ORDER BY total_sales DESC
    ");

    $sheet = createSheet($spreadsheet, 'Payment Order Status');
    $startRow = addReportHeader(
        $sheet,
        'Payment and Order Status Report',
        'Combined payment method, payment status, and order status breakdown',
        6
    );

    writeTable($sheet, $startRow, [
        ['key' => 'payment_method', 'label' => 'Payment Method', 'type' => 'text'],
        ['key' => 'payment_status', 'label' => 'Payment Status', 'type' => 'text'],
        ['key' => 'order_status', 'label' => 'Order Status', 'type' => 'text'],
        ['key' => 'total_sales', 'label' => 'Total Sales', 'type' => 'currency'],
        ['key' => 'total_quantity', 'label' => 'Quantity Sold', 'type' => 'number'],
        ['key' => 'total_orders', 'label' => 'Total Orders', 'type' => 'number'],
    ], $paymentOrderRows);


    $detailRows = fetchRows($pdo, "
        SELECT
            fs.source_order_id AS order_id,
            dt.full_date AS order_date,
            dp.product_name AS product_name,
            dp.category_name AS category_name,
            dpay.payment_method AS payment_method,
            dpay.payment_status AS payment_status,
            dos.order_status AS order_status,
            COALESCE(dc.province, 'Unknown') AS province,
            COALESCE(dc.city, 'Unknown') AS city,
            fs.quantity_sold AS quantity_sold,
            COALESCE(fs.subtotal / NULLIF(fs.quantity_sold, 0), 0) AS estimated_unit_price,
            fs.subtotal AS subtotal
        FROM fact_sales fs
        INNER JOIN dim_time dt 
            ON fs.dim_time_id = dt.dim_time_id
        INNER JOIN dim_product dp 
            ON fs.dim_product_id = dp.dim_product_id
        INNER JOIN dim_payment dpay 
            ON fs.dim_payment_id = dpay.dim_payment_id
        INNER JOIN dim_order_status dos 
            ON fs.dim_status_id = dos.dim_status_id
        INNER JOIN dim_customer dc 
            ON fs.dim_customer_id = dc.dim_customer_id
        ORDER BY dt.full_date DESC, fs.source_order_id DESC
    ");

    $sheet = createSheet($spreadsheet, 'Detailed Sales');
    $startRow = addReportHeader(
        $sheet,
        'Detailed Sales Records',
        'Detailed joined fact sales data from OLAP tables',
        12
    );

    writeTable($sheet, $startRow, [
        ['key' => 'order_id', 'label' => 'Order ID', 'type' => 'number'],
        ['key' => 'order_date', 'label' => 'Order Date', 'type' => 'date'],
        ['key' => 'product_name', 'label' => 'Product', 'type' => 'text'],
        ['key' => 'category_name', 'label' => 'Category', 'type' => 'text'],
        ['key' => 'payment_method', 'label' => 'Payment Method', 'type' => 'text'],
        ['key' => 'payment_status', 'label' => 'Payment Status', 'type' => 'text'],
        ['key' => 'order_status', 'label' => 'Order Status', 'type' => 'text'],
        ['key' => 'province', 'label' => 'Province', 'type' => 'text'],
        ['key' => 'city', 'label' => 'City', 'type' => 'text'],
        ['key' => 'quantity_sold', 'label' => 'Quantity Sold', 'type' => 'number'],
        ['key' => 'estimated_unit_price', 'label' => 'Estimated Unit Price', 'type' => 'currency'],
        ['key' => 'subtotal', 'label' => 'Subtotal', 'type' => 'currency'],
    ], $detailRows);

    $referenceSheet = createSheet($spreadsheet, 'Reference Data');
    $startRow = addReportHeader(
        $referenceSheet,
        'Reference Data',
        'Available categories, payment methods, and order statuses used in analytics filters',
        3
    );

    $categories = fetchRows($pdo, "
        SELECT category_name
        FROM dim_product
        GROUP BY category_name
        ORDER BY category_name
    ");

    $paymentMethods = fetchRows($pdo, "
        SELECT payment_method
        FROM dim_payment
        GROUP BY payment_method
        ORDER BY payment_method
    ");

    $orderStatuses = fetchRows($pdo, "
        SELECT order_status
        FROM dim_order_status
        GROUP BY order_status
        ORDER BY order_status
    ");

    $currentRow = $startRow;

    $referenceSheet->setCellValue("A{$currentRow}", 'Categories');
    $referenceSheet->getStyle("A{$currentRow}")->getFont()->setBold(true)->setSize(13);
    $currentRow++;

    $currentRow = writeTable($referenceSheet, $currentRow, [
        ['key' => 'category_name', 'label' => 'Category Name', 'type' => 'text'],
    ], $categories, false);

    $referenceSheet->setCellValue("A{$currentRow}", 'Payment Methods');
    $referenceSheet->getStyle("A{$currentRow}")->getFont()->setBold(true)->setSize(13);
    $currentRow++;

    $currentRow = writeTable($referenceSheet, $currentRow, [
        ['key' => 'payment_method', 'label' => 'Payment Method', 'type' => 'text'],
    ], $paymentMethods, false);

    $referenceSheet->setCellValue("A{$currentRow}", 'Order Statuses');
    $referenceSheet->getStyle("A{$currentRow}")->getFont()->setBold(true)->setSize(13);
    $currentRow++;

    writeTable($referenceSheet, $currentRow, [
        ['key' => 'order_status', 'label' => 'Order Status', 'type' => 'text'],
    ], $orderStatuses, false);


    foreach ($spreadsheet->getAllSheets() as $sheet) {
        setDefaultSheetStyle($sheet);
    }

    $spreadsheet->setActiveSheetIndex(0);

    $filename = 'Smart_Tech_Analytics_Report_' . date('Y-m-d_H-i-s') . '.xlsx';

    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"{$filename}\"");
    header('Cache-Control: max-age=0');
    header('Pragma: public');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo 'Excel report generation failed: ' . htmlspecialchars($e->getMessage());
}