<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orders - Smart Tech Admin</title>
  <link rel="stylesheet" href="../Assets/CSS/admin.css">
  <link rel="stylesheet" href="../Assets/CSS/adOrders.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="../Assets/CSS/notifications.css">
  <script src="../Assets/JavaScript/adminOrders.js" defer></script>
</head>

<body>
  <div class="receipt-modal-overlay" id="receiptModal">
    <div class="receipt-box">

      <button class="receipt-close-btn" onclick="closeReceiptModal()">
        &times;
      </button>

      <div class="receipt-header">
        <h2>Smart Tech</h2>
        <p>Official Purchase Receipt</p>
      </div>

      <div class="receipt-order-info">
        <div>
          <span>Order ID</span>
          <strong id="receiptOrderId">#ST-1001</strong>
        </div>

        <div>
          <span>Date</span>
          <strong id="receiptDate">June 7, 2026</strong>
        </div>

        <div>
          <span>Payment</span>
          <strong id="receiptPayment">Cash on Delivery</strong>
        </div>

        <div>
          <span>Status</span>
          <strong class="receipt-status" id="receiptStatus">Pending</strong>
        </div>
      </div>

      <div class="customer-summary">
        <div>
          <div><b>Customer Name:</b> <span id="receiptCustomerName"></span></div>
          <div><b>Address:</b> <span id="receiptAddress"></span></div>
          <div><b>Contact No.:</b> <span id="receiptContactNumber"></span></div>
          <div><b>Email:</b> <span id="receiptEmail"></span></div>
        </div>
      </div>

      <div class="receipt-products">
        <div class="receipt-products-header">
          <span>Product</span>
          <span>Qty</span>
          <span>Price</span>
          <span>Subtotal</span>
        </div>

        <div id="receiptItems">
          <!-- Product rows will be inserted by JavaScript -->
        </div>
      </div>

      <div class="receipt-summary">
        <div class="receipt-total">
          <span>Grand Total</span>
          <strong id="receiptGrandTotal">₱0.00</strong>
        </div>
      </div>

      <div class="receipt-footer">
        <p>Thank you for shopping with <strong>Smart Tech</strong>.</p>

        <div class="receipt-actions">
          <button class="receipt-print-btn" onclick="printReceipt()">
            Print Receipt
          </button>

          <button class="receipt-cancel-btn" onclick="closeReceiptModal()">
            Close
          </button>
        </div>
      </div>

    </div>
  </div>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="sb-logo">Smart Tech <span>Admin Panel</span></div>
    <div class="sb-nav">
      <div class="sb-label">Main</div>
      <a href="adminDashboard.php" class="sb-item"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
      <a href="adminAnalytics.php" class="sb-item"><i class="fa-solid fa-chart-area"></i>
        <span>Analytics</span></a>
      <a href="adminProducts.php" class="sb-item"><i class="fa-solid fa-box"></i> Products</a>
      <a href="adminOrders.php" class="sb-item active"><i class="fa-solid fa-cart-shopping"></i> Orders</a>
      <a href="adminUsers.php" class="sb-item"><i class="fa-solid fa-users"></i> Customers</a>
    </div>
    <div class="sb-bottom">
      <!-- 1. Context Popover Container for Logout Action -->
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
      <div class="topbar-title">Orders</div>
      <div class="topbar-right">
        <div class="date-chip"><i class="fa-solid fa-calendar" style="margin-right:6px;"></i>May 2026</div>
        <?php include "../reusable-notif.php"; ?>
      </div>
    </div>

    <div class="content">

      <!-- Stat Cards -->
      <div class="stat-grid">
        <div class="stat-card">
          <div class="stat-icon ic-purple"><i class="fa-solid fa-cart-shopping"></i></div>
          <div class="stat-label">Total Orders</div>
          <div class="stat-value">1,248</div>
          <div class="stat-sub"><i class="fa-solid fa-trending-up"></i> +8.1% this month</div>
        </div>
        <div class="stat-card">
          <div class="stat-icon ic-amber"><i class="fa-solid fa-clock"></i></div>
          <div class="stat-label">Pending</div>
          <div class="stat-value">34</div>
          <div class="stat-sub down"><i class="fa-solid fa-triangle-exclamation"></i> Needs action</div>
        </div>
        <div class="stat-card">
          <div class="stat-icon ic-blue"><i class="fa-solid fa-spinner"></i></div>
          <div class="stat-label">Processing</div>
          <div class="stat-value">89</div>
          <div class="stat-sub muted"><i class="fa-solid fa-minus"></i> In progress</div>
        </div>
        <div class="stat-card">
          <div class="stat-icon ic-green"><i class="fa-solid fa-truck"></i></div>
          <div class="stat-label">Delivered</div>
          <div class="stat-value">1,125</div>
          <div class="stat-sub"><i class="fa-solid fa-trending-up"></i> Delivered on time</div>
        </div>
      </div>

      <!-- Orders Table -->
      <div class="card">
        <div class="filter-bar">
          <div class="search-wrap">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="searchInput" placeholder="Search by order ID or customer…" oninput="filterTable()">
          </div>
          <select class="filter-select" id="statusFilter" onchange="filterTable()">
            <option value="">All Status</option>
            <option>Pending</option>
            <option>Processing</option>
            <option>Shipped</option>
            <option>Delivered</option>
            <option>Cancelled</option>
          </select>
        </div>

        <div style="overflow-x: auto;">
          <table class="admin-table">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>No. of Items</th>
                <th>Total</th>
                <th>Date</th>
                <th>Detail</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody id="ordersBody">
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
    let total_orders = 0;
    let pending = 0;
    let processing = 0;
    let shipped = 0;
    let delivered = 0;

    async function fetchOrders() {
      try {
        const response = await fetch('../Actions/Admin_Orders/fetch-table-orders.php');
        const result = await response.json();
        if (result.success) {
          orders = result.data;
          renderOrders(orders);
        } else {
          console.error('Error fetching orders:', result.error);
          showToast('Error fetching orders. Please try again later.', 'error');
        }
      } catch (error) {
        console.error('Fetch error:', error);
        showToast('Network error while fetching orders. Please check your connection.', 'error');
      }
    }

    fetchOrders();

    async function renderOrders(orderList, source = '') {
      const ordersBody = document.getElementById('ordersBody');
      const tbody = document.getElementById('ordersBody');
      tbody.innerHTML = orderList.map(o => `
        <tr>
          <td><span class="order-id">ORD-${o.order_id}</span></td>
          <td class="customer-col">
            <div class="customer-cell">
              <div class="cust-av">${initials(o.customer)}</div>
              <div>
                <div class="cust-name">${o.customer}</div>
                <div class="cust-email">${o.email}</div>
              </div>
            </div>
          </td>
          <td style="max-width:200px;">
            <div style="font-size:12px;font-weight:600;color:#1f2937;line-height:1.4;">${o.item_count}</div>
          </td>
          <td style="font-weight:700;color:#4E0B99;">₱${o.total_amount.toLocaleString()}</td>
          <td style="font-size:12px;color:#6b7280;">${new Date(o.order_date.replace(' ', 'T')).toLocaleString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
      })}</td >
          <td><button class="btn-outline" onclick="fetchReceiptData('${o.order_id}')">View Details</button></td>
          <td>
            <select class="status-select" onchange="updateStatus('${o.order_id}', this.value)">
              <option ${o.status === 'pending' ? 'selected' : ''}>Pending</option>
              <option ${o.status === 'processing' ? 'selected' : ''}>Processing</option>
              <option ${o.status === 'shipped' ? 'selected' : ''}>Shipped</option>
              <option ${o.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
              <option ${o.status === 'delivered' ? 'selected' : ''}>Delivered</option>
            </select>
          </td>
        </tr >
      `).join('');
      if (source === 'filtering') {
        return; // Skip updating stats when just filtering
      }
      total_orders = orderList.length;
      pending = orderList.filter(o => o.status === 'pending').length;
      processing = orderList.filter(o => o.status === 'processing').length;
      shipped = orderList.filter(o => o.status === 'shipped').length;
      delivered = orderList.filter(o => o.status === 'delivered').length;

      document.querySelector('.stat-grid .stat-card:nth-child(1) .stat-value').textContent = total_orders;
      document.querySelector('.stat-grid .stat-card:nth-child(2) .stat-value').textContent = pending;
      document.querySelector('.stat-grid .stat-card:nth-child(3) .stat-value').textContent = processing;
      document.querySelector('.stat-grid .stat-card:nth-child(4) .stat-value').textContent = delivered;
    }

    const receiptModal = document.getElementById("receiptModal");
    const receiptItems = document.getElementById("receiptItems");

    function openReceiptModal(order) {
      loadReceiptData(order);
      receiptModal.classList.add("active");
    }

    function closeReceiptModal() {
      receiptModal.classList.remove("active");
    }

    async function fetchReceiptData(orderId) {
      try {
        const response = await fetch(`../Actions/Order/fetch_receipt.php?order_id=${orderId}`);
        if (!response.ok) {
          console.error("Failed to fetch receipt data");
          return;
        }
        const data = await response.json();
        if (data.error) {
          console.error("Error fetching receipt:", data.error);
          return;
        }
        openReceiptModal(data.data);
      } catch (error) {
        console.error("Error fetching receipt data:", error);
      }
    }

    function loadReceiptData(order) {
      document.getElementById("receiptOrderId").textContent = order.order_id;
      document.getElementById("receiptDate").textContent = new Date(order.order_date.replace(' ', 'T')).toLocaleString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
      }); //date formatting can be improved
      document.getElementById("receiptPayment").textContent = order.payment_method;
      document.getElementById("receiptStatus").textContent = (order.status).charAt(0).toUpperCase() + (order.status).slice(1);
      document.getElementById("receiptCustomerName").textContent = order.customer.full_name;
      document.getElementById("receiptEmail").textContent = order.customer.email;
      document.getElementById("receiptContactNumber").textContent = order.customer.contact_number;
      document.getElementById("receiptAddress").textContent = order.customer.address_line;

      receiptItems.innerHTML = "";

      let subtotal = 0;

      order.products.forEach(function (item) {
        const itemSubtotal = item.quantity * item.price;
        subtotal += itemSubtotal;

        const row = document.createElement("div");
        row.className = "receipt-item";

        row.innerHTML = `
      <div class="receipt-product-name">
        <strong>${item.product_name}</strong>
        <small>${item.category_name}</small>
      </div>

      <span class="receipt-center">${item.quantity}</span>

      <span class="receipt-right">${formatPeso(item.price)}</span>

      <span class="receipt-right">${formatPeso(itemSubtotal)}</span>`;

        receiptItems.appendChild(row);
      });

      grandTotal = subtotal;
      document.getElementById("receiptGrandTotal").textContent = formatPeso(grandTotal);
    }

    function formatPeso(amount) {
      return "₱" + Number(amount).toLocaleString("en-PH", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      });
    }

    function printReceipt() {
      window.print();
    }

    receiptModal.addEventListener("click", function (event) {
      if (event.target === receiptModal) {
        closeReceiptModal();
      }
    });

    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape") {
        closeReceiptModal();
      }
    });


    // Call renderOrders when the page loads
    document.addEventListener('DOMContentLoaded', renderOrders);
  </script>
  <script>let dots = '../';</script>
  <script src="../Assets/JavaScript/notifications_admin.js"></script>

</body>

</html>