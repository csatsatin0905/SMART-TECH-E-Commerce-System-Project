<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customers - Smart Tech Admin</title>
  <link rel="stylesheet" href="../Assets/CSS/admin.css">
  <link rel="stylesheet" href="../Assets/CSS/adUsers.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="../Assets/JavaScript/adminUsers.js" defer></script>
</head>

<body>
  <div class="admin-layout">

    <!-- Sidebar -->
    <div class="sidebar">
      <div class="sb-logo">Smart Tech <span>Admin Panel</span></div>
      <div class="sb-nav">
        <div class="sb-label">Main</div>
        <a href="adminDashboard.php" class="sb-item"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
        <a href="adminAnalytics.php" class="sb-item"><i class="fa-solid fa-chart-area"></i>
          <span>Analytics</span></a>
        <a href="adminProducts.php" class="sb-item"><i class="fa-solid fa-box"></i> Products</a>
        <a href="adminOrders.php" class="sb-item"><i class="fa-solid fa-cart-shopping"></i> Orders</a>
        <a href="adminUsers.php" class="sb-item active"><i class="fa-solid fa-users"></i> Customers</a>
      </div>
      <div class="sb-bottom">
        <div class="profile-popover-menu" id="profileMenu">
          <button type="button" class="popover-item logout-action" onclick="handleLogout()">
            <i class="fa-solid fa-right-from-bracket"></i> Log Out
          </button>
        </div>

        <!-- 2. Interactive Profile Badge Trigger Area -->
        <div class="admin-badge" id="profileMenuTrigger">
          <div class="admin-av">A</div>
          <div class="admin-info">Admin <span>smarttech@admin.com</span></div>
          <i class="fa-solid fa-ellipsis-vertical profile-menu-dots"></i>
        </div>
      </div>
    </div>

    <!-- Main -->
    <div class="main">
      <div class="topbar">
        <div class="topbar-title">Customers</div>
        <div class="topbar-right">
          <div class="date-chip"><i class="fa-solid fa-calendar" style="margin-right:6px;"></i>May 2026</div>
          <div class="notif-wrapper">
            <!-- The bell icon trigger button -->
            <div class="notif-btn" id="notifTrigger">
              <i class="fa-solid fa-bell"></i>
              <div class="notif-dot"></div>
            </div>

            <!-- Small Floating Scrollable Notifications Center Window Container -->
            <div class="notif-dropdown-window" id="notifDropdown">
              <div class="nd-header">
                <h3>Notifications</h3>
                <button type="button" class="nd-mark-read-btn">Mark all read</button>
              </div>

              <!-- Scrollable Notification Feed -->
              <div class="nd-body-scroller">
                <div class="nd-item unread">
                  <div class="nd-icon purple"><i class="fa-solid fa-cart-shopping"></i></div>
                  <div class="nd-content">
                    <p>New order received <strong>#ST-9482</strong> from John D.</p>
                    <span class="nd-time">2 mins ago</span>
                  </div>
                </div>
                <div class="nd-item unread">
                  <div class="nd-icon amber"><i class="fa-solid fa-triangle-exclamation"></i></div>
                  <div class="nd-content">
                    <p>Stock Alert: <strong>Ryzen 9 7950X</strong> is down to 2 units.</p>
                    <span class="nd-time">1 hr ago</span>
                  </div>
                </div>
                <div class="nd-item">
                  <div class="nd-icon green"><i class="fa-solid fa-peso-sign"></i></div>
                  <div class="nd-content">
                    <p>Daily sales payout total reached target goal of ₱80k.</p>
                    <span class="nd-time">5 hrs ago</span>
                  </div>
                </div>
                <div class="nd-item">
                  <div class="nd-icon blue"><i class="fa-solid fa-user-plus"></i></div>
                  <div class="nd-content">
                    <p>New customer account verified: <strong>Maria S.</strong></p>
                    <span class="nd-time">1 day ago</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="content">

        <!-- Stat Cards -->
        <div class="stat-grid">
          <div class="stat-card">
            <div class="stat-icon ic-purple"><i class="fa-solid fa-users"></i></div>
            <div class="stat-label">Total Customers</div>
            <div class="stat-value" id="totalCustomers">3,671</div>
            <div class="stat-sub"><i class="fa-solid fa-trending-up"></i> +5.3% this month</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon ic-green"><i class="fa-solid fa-user-check"></i></div>
            <div class="stat-label">Active</div>
            <div class="stat-value" id="activeCustomers">3,598</div>
            <div class="stat-sub muted"><i class="fa-solid fa-minus"></i> Currently enabled</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon ic-red"><i class="fa-solid fa-user-xmark"></i></div>
            <div class="stat-label">Inactive</div>
            <div class="stat-value" id="inactiveCustomers">73</div>
            <div class="stat-sub muted"><i class="fa-solid fa-minus"></i> Currently disabled</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon ic-blue"><i class="fa-solid fa-user-plus"></i></div>
            <div class="stat-label">New This Month</div>
            <div class="stat-value" id="newThisMonth">194</div>
            <div class="stat-sub"><i class="fa-solid fa-trending-up"></i> Growing steadily</div>
          </div>
        </div>

        <!-- Customers Table -->
        <div class="card">
          <div class="filter-bar">
            <div class="search-wrap">
              <i class="fa-solid fa-magnifying-glass"></i>
              <input type="text" id="searchInput" placeholder="Search by name or email…" oninput="filterTable()">
            </div>
            <select class="filter-select" id="statusFilter" onchange="filterTable()">
              <option value="">All Status</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>

          <div style="overflow-x: auto;">
            <table class="admin-table">
              <thead>
                <tr>
                  <th>Customer</th>
                  <th>Phone</th>
                  <th>Orders</th>
                  <th>Total Spent</th>
                  <th>Registered</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="usersBody">
                <!-- Rows injected by JS -->
              </tbody>
            </table>
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

  <!-- Toast -->
  <div class="toast" id="toast"></div>

  <script>
    async function fetchCustomers() {
      try {
        const response = await fetch('../Actions/Admin_Customers/fetch-table-customers.php');
        const result = await response.json();
        if (result.success) {
          users = result.data; // Store the fetched users in a global variable for filtering
          renderTable(result.data);
        } else {
          showToast('Error fetching customers: ' + result.error, 'error');
        }
      } catch (error) {
        showToast('Network error: ' + error.message, 'error');
      }
    }

    // Fetch customers when the page loads
    document.addEventListener('DOMContentLoaded', fetchCustomers);
  </script>

</body>

</html>