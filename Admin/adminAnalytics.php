<?php
session_start();
require_once '../Database/db.php';
require_once '../Database/runETL.php';

$stmt = $pdo->prepare("SELECT category_name FROM dim_product GROUP BY category_name");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT payment_method FROM dim_payment GROUP BY payment_method");
$stmt->execute();
$paymentMethods = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT order_status FROM dim_order_status GROUP BY order_status");
$stmt->execute();
$orderStatuses = $stmt->fetchAll(PDO::FETCH_ASSOC);

//KPI QUERIES
$stmt = $pdo->prepare("SELECT SUM(subtotal) AS total_sales FROM fact_sales fs
INNER JOIN dim_order_status dos ON fs.dim_status_id = dos.dim_status_id
WHERE dos.order_status = 'delivered'");
$stmt->execute();
$totalSales = $stmt->fetch(PDO::FETCH_ASSOC)['total_sales'];

$stmt = $pdo->prepare("SELECT COUNT(DISTINCT source_order_id) AS total_orders FROM fact_sales fs
INNER JOIN dim_order_status dos ON fs.dim_status_id = dos.dim_status_id
WHERE dos.order_status = 'delivered'");
$stmt->execute();
$totalOrders = $stmt->fetch(PDO::FETCH_ASSOC)['total_orders'];

$stmt = $pdo->prepare("SELECT COALESCE(SUM(fs.quantity_sold), 0) AS total_products_sold FROM fact_sales fs
INNER JOIN dim_order_status dos 
ON fs.dim_status_id = dos.dim_status_id
WHERE dos.order_status = 'delivered'");
$stmt->execute();
$totalProductsSold = $stmt->fetch(PDO::FETCH_ASSOC)['total_products_sold'];

$stmt = $pdo->prepare("SELECT 
    COALESCE(
        SUM(fs.subtotal) / NULLIF(COUNT(DISTINCT fs.source_order_id), 0),
        0
    ) AS average_order_value
FROM fact_sales fs
INNER JOIN dim_order_status dos 
    ON fs.dim_status_id = dos.dim_status_id
WHERE dos.order_status = 'delivered';
");
$stmt->execute();
$averageOrderValue = $stmt->fetch(PDO::FETCH_ASSOC)['average_order_value'];

$stmt = $pdo->prepare("SELECT 
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
LIMIT 1");
$stmt->execute();
$topSellingProduct = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT 
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
LIMIT 1");
$stmt->execute();
$leastSellingProduct = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT 
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
LIMIT 1");

$stmt->execute();
$bestSellingCategory = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT 
    dpay.payment_method,
    COUNT(DISTINCT fs.source_order_id) AS total_orders
FROM fact_sales fs
INNER JOIN dim_payment dpay 
    ON fs.dim_payment_id = dpay.dim_payment_id
GROUP BY dpay.payment_method
ORDER BY total_orders DESC
LIMIT 1");

$stmt->execute();
$mostUsedPaymentMethod = $stmt->fetch(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Smart Tech Admin</title>
  <link rel="stylesheet" href="../Assets/CSS/admin.css">
  <link rel="stylesheet" href="../Assets/CSS/adDashboard.css">
  <link rel="stylesheet" href="../node_modules/@fortawesome/fontawesome-free/css/all.min.css">
  <script src="../Assets/JavaScript/chart.umd.min.js"></script>
  <script src="../Assets/JavaScript/adminDashboard.js" defer></script>
  <script src="../Actions/Analytics/chart.umd.min.js" defer></script>
  <link rel="stylesheet" href="../Assets/CSS/notifications.css">
  <style>
    select {
      min-width: 50px;
      padding: 10px 10px 10px 8px;

      font-size: 12px;
      font-weight: 500;
      color: #1f2937;

      background-color: #ffffff;
      border: 1px solid #d1d5db;
      border-radius: 10px;

      outline: none;
      cursor: pointer;

      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
      transition: all 0.25s ease;
    }

    select:hover {
      border-color: #2563eb;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
    }

    select:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
    }

    @media (max-width: 768px) {
      select {
        width: 100%;
      }
    }
  </style>
</head>

<body>
  <div class="admin-layout">

    <div class="sidebar">
      <div class="sb-logo">Smart Tech <span>Admin Panel</span></div>
      <div class="sb-nav">
        <div class="sb-label">Main</div>
        <a href="adminDashboard.php" class="sb-item"><i class="fa-solid fa-gauge-high"></i>
          <span>Dashboard</span></a>
        <a href="adminAnalytics.php" class="sb-item active"><i class="fa-solid fa-chart-area"></i>
          <span>Analytics</span></a>
        <a href="adminProducts.php" class="sb-item"><i class="fa-solid fa-box"></i> <span>Products</span></a>
        <a href="adminOrders.php" class="sb-item"><i class="fa-solid fa-cart-shopping"></i> <span>Orders</span></a>
        <a href="adminUsers.php" class="sb-item"><i class="fa-solid fa-users"></i> <span>Customers</span></a>
      </div>

      <div class="sb-bottom">
        <div class="profile-popover-menu" id="profileMenu">
          <button type="button" class="popover-item logout-action" onclick="handleLogout()">
            <i class="fa-solid fa-right-from-bracket"></i> Log Out
          </button>
        </div>

        <div class="admin-badge" id="profileMenuTrigger">
          <div class="admin-av">A</div>
          <div class="admin-info">Admin <span>smarttech@admin.com</span></div>
          <i class="fa-solid fa-ellipsis-vertical profile-menu-dots"></i>
        </div>
      </div>
    </div>

    <div class="main">
      <div class="topbar">
        <div class="topbar-title">Dashboard</div>
        <div class="topbar-right">
          <div class="date-chip"><i class="fa-solid fa-calendar" style="margin-right:6px;"></i>May 2026</div>

          <?php include "../reusable-notif.php"; ?>

        </div>
      </div>

      <div style="display: flex; justify-content: flex-end; padding-top: 15px; padding-right: 30px;">
        <a href="../Actions/Analytics/export_analytics_report.php" style="
      display:inline-flex;
      align-items:center;
      gap:8px;
      padding:10px 16px;
      background:#4E0B99;
      color:white;
      border-radius:10px;
      text-decoration:none;
      font-weight:600;
      font-size:14px;
   ">
          <i class="fa-solid fa-file-excel"></i>
          Export Excel Report
        </a>
      </div>



      <div class="content">
        <div class="stat-grid">
          <div class="stat-card">
            <div class="stat-icon ic-purple"><i class="fa-solid fa-peso-sign"></i></div>
            <div class="stat-label">Total Sales</div>
            <div class="stat-value">₱<?= number_format($totalSales, 2) ?></div>
          </div>
          <div class="stat-card">
            <div class="stat-icon ic-green"><i class="fa-solid fa-cart-shopping"></i></div>
            <div class="stat-label">Total Orders</div>
            <div class="stat-value"> <?= $totalOrders ?? 'N/A' ?></div>
          </div>
          <div class="stat-card">
            <div class="stat-icon ic-amber"><i class="fa-solid fa-box"></i></div>
            <div class="stat-label">Total Products Sold</div>
            <div class="stat-value"><?= $totalProductsSold ?? 'N/A' ?></div>
          </div>
          <div class="stat-card">
            <div class="stat-icon ic-blue"><i class="fa-solid fa-cart-arrow-down"></i></div>
            <div class="stat-label">Average Order Value</div>
            <div class="stat-value">₱<?= number_format($averageOrderValue, 2) ?></div>
          </div>
          <div class="stat-card">
            <div class="stat-icon ic-purple"><i class="fa-solid fa-star"></i></div>
            <div class="stat-label">Top Selling Product</div>
            <div class="stat-value"><?= $topSellingProduct['product_name'] ?? 'N/A' ?></div>
          </div>
          <div class="stat-card">
            <div class="stat-icon ic-green"><i class="fa-solid fa-arrow-trend-down"></i></div>
            <div class="stat-label">Least Selling Product</div>
            <div class="stat-value"><?= $leastSellingProduct['product_name'] ?? 'N/A' ?></div>
          </div>
          <div class="stat-card">
            <div class="stat-icon ic-amber"><i class="fa-solid fa-box"></i></div>
            <div class="stat-label">Best Selling Category</div>
            <div class="stat-value"><?= $bestSellingCategory['category_name'] ?? 'N/A' ?></div>
          </div>
          <div class="stat-card">
            <div class="stat-icon ic-blue"><i class="fa-solid fa-users"></i></div>
            <div class="stat-label">Most Used Payment Method</div>
            <div class="stat-value"><?= $mostUsedPaymentMethod['payment_method'] ?? 'N/A' ?></div>
          </div>
        </div>
        <div class="bottom-row" style="display: grid; grid-template-columns: 1.6fr 1.4fr; gap: 24px;">
          <!-- sales trend chart -->
          <div class="card">
            <div class="card-head">
              <div class="card-title">Sales Overview</div>
              <div class="period-tabs">
                <select data-filter="group_by" data-chart="sales">
                  <option value="day">Day</option>
                  <option value="month" selected>Month</option>
                  <option value="quarter">Quarter</option>
                  <option value="year">Year</option>
                </select>
                <select data-filter="category" data-chart="sales">
                  <option value="">All Categories</option>
                  <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['category_name'] ?>"><?= $category['category_name'] ?></option>
                  <?php endforeach; ?>
                </select>
                <select data-filter="paymentMethod" data-chart="sales">
                  <option value="">All Payment Methods</option>
                  <?php foreach ($paymentMethods as $method): ?>
                    <option value="<?= $method['payment_method'] ?>"><?= $method['payment_method'] ?></option>
                  <?php endforeach; ?>
                </select>
                <select data-filter="orderStatus" data-chart="sales">
                  <option value="">All Order Statuses</option>
                  <?php foreach ($orderStatuses as $status): ?>
                    <option value="<?= $status['order_status'] ?>"><?= $status['order_status'] ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div style="padding: 20px;">
              <div style="position: relative; height: 260px;">
                <canvas id="salesTrendChart"></canvas>
              </div>
            </div>
          </div>
          <!-- sales per product -->
          <div class="card">
            <div class="card-head">
              <div class="card-title">Sales Per Product</div>
              <div class="period-tabs">
                <select data-filter="group_by" data-chart="sales_product">
                  <option value="category" selected>View By Category</option>
                  <option value="product">View By Product</option>
                </select>
                <select data-filter="orderStatus" data-chart="sales_product">
                  <option value="">All Order Statuses</option>
                  <?php foreach ($orderStatuses as $status): ?>
                    <option value="<?= $status['order_status'] ?>">
                      <?= $status['order_status'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <select data-filter="time_filter" data-chart="sales_product">
                  <option value="">All Time</option>
                  <option value="this_year">This Year</option>
                  <option value="this_month">This Month</option>
                </select>
              </div>
            </div>
            <div style="padding: 20px;">
              <div style="position: relative; height: 260px;">
                <canvas id="salesProductChart"></canvas>
              </div>
            </div>
          </div>
          <!-- sales per location -->
          <div class="card">
            <div class="card-head">
              <div class="card-title">Sales Per Location</div>
              <div class="period-tabs">
                <select data-filter="group_by" data-chart="sales_location">
                  <option value="province" selected>View By Province</option>
                  <option value="city">View By City</option>
                </select>
                <select data-filter="category" data-chart="sales_location">
                  <option value="">All Categories</option>
                  <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['category_name'] ?>"><?= $category['category_name'] ?></option>
                  <?php endforeach; ?>
                </select>
                <select data-filter="paymentMethod" data-chart="sales_location">
                  <option value="">All Payment Methods</option>
                  <?php foreach ($paymentMethods as $method): ?>
                    <option value="<?= $method['payment_method'] ?>">
                      <?= $method['payment_method'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <select data-filter="orderStatus" data-chart="sales_location">
                  <option value="">All Order Statuses</option>
                  <?php foreach ($orderStatuses as $status): ?>
                    <option value="<?= $status['order_status'] ?>">
                      <?= $status['order_status'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div style="padding: 20px;">
              <div style="position: relative; height: 260px;">
                <canvas id="salesLocationChart"></canvas>
              </div>
            </div>
          </div>
          <!-- payment and order status chart -->
          <div class="card">
            <div class="card-head">
              <div class="card-title">Payment and Order Status Chart</div>
              <div class="period-tabs">
                <select data-filter="group_by" data-chart="sales_payment_order">
                  <option value="payment_method" selected>View By Payment Method</option>
                  <option value="order_status">View By Order Status</option>
                </select>
                <select data-filter="value_by" data-chart="sales_payment_order">
                  <option value="sales_amount" selected>Sales Amount</option>
                  <option value="order_count">Order Count</option>
                  <option value="quantity_sold">Quantity Sold</option>
                </select>
                <select data-filter="category" data-chart="sales_payment_order">
                  <option value="">All Categories</option>
                  <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['category_name'] ?>"><?= $category['category_name'] ?></option>
                  <?php endforeach; ?>
                </select>
                <select data-filter="time_filter" data-chart="sales_payment_order">
                  <option value="">All Time</option>
                  <option value="this_year">This Year</option>
                  <option value="this_month">This Month</option>
                </select>

              </div>
            </div>
            <div style="padding: 20px;">
              <div style="position: relative; height: 260px;">
                <canvas id="salesPaymentOrderChart"></canvas>
              </div>
            </div>
          </div>


        </div>

      </div>
    </div>
  </div>

  //Logout Modal
  <div class="modal-overlay" id="logoutModal">
    <div class="modal" style="width:380px;">
      <div class="modal-head">
        <h3>Confirm Logout</h3>
        <button class="btn-icon" onclick="closeLogout()"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <div class="modal-body">
        <p style="text-align: center; color: #4b5563; padding: 10px 0; margin: 0;">
          <strong>Are you sure you want to log out of the admin panel?</strong>
        </p>
      </div>
      <div class="modal-foot" style="justify-content: center;">
        <button class="btn-outline" onclick="closeLogout()">Cancel</button>
        <button class="btn-primary" style="background:#4E0B99; border: none;" onclick="confirmLogoutAction()">
          <i class="fa-solid fa-right-from-bracket"></i> Log Out
        </button>
      </div>
    </div>
  </div>

  <script src="../Assets/JavaScript/analytics/fetch_sales_trend.js"></script>
  <script src="../Assets/JavaScript/analytics/fetch_sales_product.js"></script>
  <script src="../Assets/JavaScript/analytics/fetch_sales_location.js"></script>
  <script src="../Assets/JavaScript/analytics/fetch_sales_payment_order.js"></script>
  <script>let dots = '../';</script>
  <script src="../Assets/JavaScript/notifications_admin.js"></script>
</body>

</html>