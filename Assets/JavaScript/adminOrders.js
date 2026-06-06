  let orders = [
    { id:'#ST-0001', customer:'John Dela Cruz',   email:'john@email.com',  product:'RTX 4090 Windforce',         qty:1, total:5000,  date:'May 24, 2026', status:'Shipped'    },
    { id:'#ST-0002', customer:'Maria Santos',     email:'maria@email.com', product:'AMD Ryzen 9 7950X',          qty:1, total:28000, date:'May 24, 2026', status:'Processing' },
    { id:'#ST-0003', customer:'Carlo Reyes',      email:'carlo@email.com', product:'Corsair 32GB DDR5',          qty:2, total:25000, date:'May 23, 2026', status:'Pending'    },
    { id:'#ST-0004', customer:'Ana Lopez',        email:'ana@email.com',   product:'ASUS ROG Strix B650E',       qty:1, total:18000, date:'May 23, 2026', status:'Shipped'    },
    { id:'#ST-0005', customer:'Benjamin Torres',  email:'ben@email.com',   product:'Seasonic 850W Gold',         qty:1, total:7200,  date:'May 22, 2026', status:'Processing' },
    { id:'#ST-0006', customer:'Grace Villanueva', email:'grace@email.com', product:'Samsung 990 Pro 2TB SSD',    qty:1, total:9800,  date:'May 22, 2026', status:'Shipped'    },
    { id:'#ST-0007', customer:'Rico Magno',       email:'rico@email.com',  product:'Noctua NH-D15 CPU Cooler',   qty:1, total:4500,  date:'May 21, 2026', status:'Pending'    },
    { id:'#ST-0008', customer:'Liza Navarro',     email:'liza@email.com',  product:'MSI RTX 4080 SUPRIM X',      qty:1, total:45000, date:'May 20, 2026', status:'Cancelled'  },
    { id:'#ST-0009', customer:'Jun Bautista',     email:'jun@email.com',   product:'Intel Core i9-14900K',       qty:1, total:32000, date:'May 19, 2026', status:'Shipped'    },
    { id:'#ST-0010', customer:'Cathy Flores',     email:'cathy@email.com', product:'Gigabyte Z790 AORUS Elite',  qty:1, total:22000, date:'May 18, 2026', status:'Processing' },
  ];

  const pillMap = {
    'Pending':    'pill-pending',
    'Processing': 'pill-process',
    'Shipped':    'pill-shipped',
    'Cancelled':  'pill-cancelled',
  };

  function initials(name) {
    return name.split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase();
  }

  function renderTable(data) {
    const tbody = document.getElementById('ordersBody');
    tbody.innerHTML = data.map(o => `
      <tr>
        <td><span class="order-id">${o.id}</span></td>
        <td>
          <div class="customer-cell">
            <div class="cust-av">${initials(o.customer)}</div>
            <div>
              <div class="cust-name">${o.customer}</div>
              <div class="cust-email">${o.email}</div>
            </div>
          </div>
        </td>
        <td style="max-width:200px;">
          <div style="font-size:12px;font-weight:600;color:#1f2937;line-height:1.4;">${o.product}</div>
        </td>
        <td style="text-align:center;font-weight:600;">${o.qty}</td>
        <td style="font-weight:700;color:#4E0B99;">₱${o.total.toLocaleString()}</td>
        <td style="font-size:12px;color:#6b7280;">${o.date}</td>
        <td>
          <select class="status-select" onchange="updateStatus('${o.id}', this.value)">
            <option ${o.status==='Pending'    ? 'selected' : ''}>Pending</option>
            <option ${o.status==='Processing' ? 'selected' : ''}>Processing</option>
            <option ${o.status==='Shipped'    ? 'selected' : ''}>Shipped</option>
            <option ${o.status==='Cancelled'  ? 'selected' : ''}>Cancelled</option>
          </select>
        </td>
      </tr>
    `).join('');
  }

  function filterTable() {
    const q  = document.getElementById('searchInput').value.toLowerCase();
    const st = document.getElementById('statusFilter').value;
    const filtered = orders.filter(o =>
      (o.id.toLowerCase().includes(q) || o.customer.toLowerCase().includes(q)) &&
      (st === '' || o.status === st)
    );
    renderTable(filtered);
  }

  function updateStatus(orderId, newStatus) {
    const o = orders.find(x => x.id === orderId);
    if (o) {
      o.status = newStatus;
      showToast(`Order ${orderId} → ${newStatus}`, 'success');
    }
  }

  function showToast(msg, type = '') {
    const t = document.getElementById('toast');
    t.innerHTML = '<i class="fa-solid fa-circle-check" style="margin-right:6px;"></i>' + msg;
    t.className = 'toast show ' + type;
    setTimeout(() => t.className = 'toast', 3000);
  }

  renderTable(orders);
  // Get elements from the DOM
  const notifTrigger = document.getElementById('notifTrigger');
  const notifDropdown = document.getElementById('notifDropdown');
  const profileTrigger = document.getElementById('profileMenuTrigger');
  const profileMenu = document.getElementById('profileMenu');

  // Toggle Notification Center Dropdown
  if (notifTrigger && notifDropdown) {
    notifTrigger.addEventListener('click', (e) => {
      e.stopPropagation();
      notifDropdown.classList.toggle('show');
      if (profileMenu) profileMenu.classList.remove('show'); // Hide profile if open
    });
  }

  // Toggle Profile Popover Menu
  if (profileTrigger && profileMenu) {
    profileTrigger.addEventListener('click', (e) => {
      e.stopPropagation();
      profileMenu.classList.toggle('show');
      if (notifDropdown) notifDropdown.classList.remove('show'); // Hide notifications if open
    });
  }

  // Close active dropdowns automatically if clicking anywhere outside them
  document.addEventListener('click', (e) => {
    if (notifDropdown && !notifDropdown.contains(e.target) && e.target !== notifTrigger) {
      notifDropdown.classList.remove('show');
    }
    if (profileMenu && !profileMenu.contains(e.target) && !profileTrigger.contains(e.target)) {
      profileMenu.classList.remove('show');
    }
  });

// Log out function
function handleLogout() {
  // Closing profile menu popover
  const profileMenu = document.getElementById('profileMenu');
  if (profileMenu) profileMenu.classList.remove('show');

  // Show custom logout modal
  document.getElementById('logoutModal').classList.add('show');
}

 // 'Cancel' o 'X' - Close Modal
function closeLogout() {
  document.getElementById('logoutModal').classList.remove('show');
}

 // Log-out destination redirection
function confirmLogoutAction() {
  window.location.href = '../Admin/adminLog-in.php';
}