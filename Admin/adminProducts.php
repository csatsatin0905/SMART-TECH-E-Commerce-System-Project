<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products - Smart Tech Admin</title>
  <link rel="stylesheet" href="../Assets/CSS/admin.css">
  <link rel="stylesheet" href="../Assets/CSS/adProducts.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="../Assets/JavaScript/adminProducts.js" defer></script>
</head>

<body>
  <div class="admin-layout">

    <!-- Sidebar -->
    <div class="sidebar">
      <div class="sb-logo">Smart Tech <span>Admin Panel</span></div>
      <div class="sb-nav">
        <div class="sb-label">Main</div>
        <a href="adminDashboard.php" class="sb-item"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
        <a href="adminProducts.php" class="sb-item active"><i class="fa-solid fa-box"></i> Products</a>
        <a href="adminOrders.php" class="sb-item"><i class="fa-solid fa-cart-shopping"></i> Orders</a>
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

    <!-- Main Content -->
    <div class="main">
      <div class="topbar">
        <div class="topbar-title">Products</div>
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
            <div class="stat-icon ic-purple"><i class="fa-solid fa-box"></i></div>
            <div class="stat-label">Total Products</div>
            <div class="stat-value" id="totalProducts">94</div>
            <div class="stat-sub muted"><i class="fa-solid fa-minus"></i> Across 9 categories</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon ic-green"><i class="fa-solid fa-circle-check"></i></div>
            <div class="stat-label">In Stock</div>
            <div class="stat-value" id="inStock">88</div>
            <div class="stat-sub"><i class="fa-solid fa-trending-up"></i> Good availability</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon ic-amber"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div class="stat-label">Low Stock</div>
            <div class="stat-value" id="lowStock">6</div>
            <div class="stat-sub down"><i class="fa-solid fa-trending-down"></i> Needs restocking (&lt;11)</div>
          </div>
          <div class="stat-card">
            <div class="stat-icon ic-red"><i class="fa-solid fa-ban"></i></div>
            <div class="stat-label">Out of Stock</div>
            <div class="stat-value" id="outOfStock">0</div>
            <div class="stat-sub muted"><i class="fa-solid fa-minus"></i> None out of stock</div>
          </div>
        </div>

        <!-- Products Table -->
        <div class="card">
          <div class="filter-bar">
            <div class="search-wrap">
              <i class="fa-solid fa-magnifying-glass"></i>
              <input type="text" id="searchInput" placeholder="Search products…" oninput="filterTable()">
            </div>
            <select class="filter-select" id="categoryFilter" onchange="filterTable()">
              <option value="">All Categories</option>
              <option>GPU</option>
              <option>CPU</option>
              <option>Motherboard</option>
              <option>RAM</option>
              <option>Storage</option>
              <option>PSU</option>
              <option>Cooling</option>
              <option>Accessories</option>
            </select>
            <div style="margin-left:auto;">
              <button class="btn-primary" onclick="openModal()">
                <i class="fa-solid fa-plus"></i> Add Product
              </button>
            </div>
          </div>

          <div style="overflow-x: auto;">
            <table class="admin-table" id="productsTable">
              <thead>
                <tr>
                  <th>Product</th>
                  <th>Category</th>
                  <th>Price</th>
                  <th>Stock</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="productsBody"><!--js data will be placed here--></tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Add / Edit Modal -->
  <div class="modal-overlay" id="productModal">
    <div class="modal">
      <div class="modal-head">
        <h3 id="modalTitle">Add Product</h3>
        <button class="btn-icon" onclick="closeModal()"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <div class="modal-body">
        <!-- Image Field -->
        <div class="form-row">
          <div class="form-group" style="grid-column: 1 / -1;">
            <label>Product Image</label>
            <div class="file-upload-container">
              <label for="productImage" class="file-upload-square">
                <span class="upload-plus">+</span>
                <span class="upload-text">Upload Image</span>

                <input type="file" id="productImage" name="product_image"
                  accept="image/jpg, image/jpeg, image/png, image/webp" hidden>
              </label>
            </div>
            <div id="imagePreview" class="image-preview"></div>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group" style="grid-column: 1 / -1;">
            <label>Product Name</label>
            <input type="text" id="fName" placeholder="e.g. GIGABYTE RTX 4090 Windforce">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group" style="grid-column: 1 / -1;">
            <label>SPECIFICATIONS</label>
            <textarea id="fSpecs" placeholder="Enter product specifications, one per line..."></textarea>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Category</label>
            <select id="fCategory">
              <option value="1">Accessories</option>
              <option value="2">Case</option>
              <option value="3">Cooling System</option>
              <option value="4">CPU</option>
              <option value="5">GPU</option>
              <option value="6">Motherboard</option>
              <option value="7">PSU</option>
              <option value="8">RAM</option>
              <option value="9">Storage</option>
            </select>
          </div>
          <div class="form-group">
            <label>Price (₱)</label>
            <input type="number" id="fPrice" placeholder="0">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Stock Quantity</label>
            <input type="number" id="fStock" placeholder="0">
          </div>
          <!-- <div class="form-group">
          <label>SKU</label>
          <input type="text" id="fSku" placeholder="e.g. GPU-001">
        </div> -->
        </div>
      </div>
      <div class="modal-foot">
        <button class="btn-outline" onclick="closeModal()">Cancel</button>
        <button class="btn-primary" onclick="saveProduct()"><i class="fa-solid fa-check"></i> Save Product</button>
      </div>
    </div>
  </div>

  <!-- Delete Modal -->
  <div class="modal-overlay" id="deleteModal">
    <div class="modal" style="width:380px;">
      <div class="modal-head">
        <h3>Delete Product</h3>
        <button class="btn-icon" onclick="closeDelete()"><i class="fa-solid fa-xmark"></i></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete <strong id="deleteProductName"></strong>?</p>
      </div>
      <div class="modal-foot" style="justify-content: center;">
        <button class="btn-outline" onclick="closeDelete()">Cancel</button>
        <button class="btn-primary" style="background:#dc2626;" onclick="confirmDelete()">Delete</button>
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

  <div class="toast" id="toast"></div>


</body>

</html>