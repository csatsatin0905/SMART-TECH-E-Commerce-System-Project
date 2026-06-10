<?php
session_start();
require_once '../Database/db.php';

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

$stmt = $pdo->prepare("SELECT COUNT(DISTINCT dim_customer_id) AS total_customers FROM fact_sales");
$stmt->execute();
$totalCustomers = $stmt->fetch(PDO::FETCH_ASSOC)['total_customers'];

//code for recent orders from oltp
$sql =
  "
SELECT 
    o.order_id,
    CONCAT(u.first_name, ' ', u.last_name) AS customer_name,

    (
        SELECT p.product_name
        FROM order_items oi
        INNER JOIN products p 
            ON oi.product_id = p.product_id
        WHERE oi.order_id = o.order_id
        ORDER BY oi.order_item_id ASC
        LIMIT 1
    ) AS sample_product,

    o.total_amount,
    o.order_status,
    o.order_date
FROM orders o
INNER JOIN users u
    ON o.user_id = u.user_id
ORDER BY o.order_date DESC
LIMIT 5;";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);


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
        <a href="adminDashboard.php" class="sb-item active"><i class="fa-solid fa-gauge-high"></i>
          <span>Dashboard</span></a>
        <a href="adminAnalytics.php" class="sb-item"><i class="fa-solid fa-chart-area"></i>
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

      <div class="content">
        <div class="stat-grid">
          <div class="stat-card">
            <div class="stat-icon ic-purple"><i class="fa-solid fa-peso-sign"></i></div>
            <div class="stat-label">Total Sales</div>
            <div class="stat-value">₱<?php echo number_format($totalSales, 2); ?></div>
          </div>
          <div class="stat-card">
            <div class="stat-icon ic-green"><i class="fa-solid fa-cart-shopping"></i></div>
            <div class="stat-label">Total Orders</div>
            <div class="stat-value"><?php echo $totalOrders; ?></div>
          </div>
          <div class="stat-card">
            <div class="stat-icon ic-amber"><i class="fa-solid fa-box"></i></div>
            <div class="stat-label">Products Sold</div>
            <div class="stat-value"><?php echo $totalProductsSold; ?></div>
          </div>
          <div class="stat-card">
            <div class="stat-icon ic-blue"><i class="fa-solid fa-users"></i></div>
            <div class="stat-label">Customers</div>
            <div class="stat-value"><?php echo $totalCustomers; ?></div>
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
              </div>
            </div>
            <div style="padding: 20px;">
              <div style="position: relative; height: 260px;">
                <canvas id="salesTrendChart"></canvas>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-head">
              <div class="card-title">Recent Orders</div>
            </div>
            <div style="padding: 8px 20px 20px;">
              <?php foreach ($recentOrders as $order): ?>
                <div class="order-row"
                  style="display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 0.5px solid #f5f0ff;">
                  <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="order-thumb"
                      style="width:36px;height:36px;border-radius:8px;background:#f3e8ff;color:#4E0B99;display:flex;align-items:center;justify-content:center;">
                      <i class="fa-solid fa-box"></i>
                    </div>
                    <div>
                      <div class="order-name" style="font-size:13px;font-weight:600;color:#1f2937;">
                        <?php echo htmlspecialchars($order['sample_product']); ?>
                      </div>
                      <div class="order-meta" style="font-size:11px;color:#9ca3af;">
                        <?php echo htmlspecialchars($order['customer_name']); ?> ·
                        <?php echo date('M j, Y, g:i a', strtotime($order['order_date'])); ?>
                      </div>
                    </div>
                  </div>
                  <div class="order-right" style="text-align: right;">
                    <div class="order-price" style="font-size:13px;font-weight:700;color:#1f2937;margin-bottom:2px;">
                      ₱
                      <?php echo number_format($order['total_amount'], 2); ?>
                    </div>
                    <span class="pill pill-<?php echo strtolower($order['order_status']); ?>">
                      <?php echo ucfirst($order['order_status']); ?>
                    </span>
                  </div>
                </div>
              <?php endforeach; ?>
              <a href="adminOrders.php" class="view-all-link"
                style="display:block;text-align:center;font-size:12px;color:#4E0B99;font-weight:600;text-decoration:none;margin-top:16px;">View
                all orders →</a>
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
  <script>let dots = '../';</script>
  <script src="../Assets/JavaScript/notifications_admin.js"></script>


</body>

</html>