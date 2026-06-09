
let orders = [];

const pillMap = {
  'Pending': 'pill-pending',
  'Processing': 'pill-process',
  'Shipped': 'pill-shipped',
  'Cancelled': 'pill-cancelled',
};

function initials(name) {
  return name.split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase();
}

function filterTable() {
  const q = document.getElementById('searchInput').value.toLowerCase();
  const st = document.getElementById('statusFilter').value;
  const filtered = orders.filter(o =>
    (o.order_id == q || o.customer.toLowerCase().includes(q)) &&
    (st === '' || o.status === st.toLowerCase())
  );
  renderOrders(filtered, 'filtering');
}

async function updateStatus(orderId, newStatus) {
  const formData = new FormData();
  formData.append('order_id', orderId);
  formData.append('status', newStatus.trim().toLowerCase());
  try {
    const response = await fetch('../Actions/Admin_Orders/change_status.php', {
      method: 'POST',
      body: formData
    });
    const result = await response.json();
    if (result.success) {
      showToast(result.message, 'success');
    } else {
      showToast(result.error, 'error');
    }
  } catch (error) {
    console.error('Error updating order status:', error);
    showToast('Error updating order status. Please try again.', 'error');
  }

}

function showToast(msg, type = '') {
  const t = document.getElementById('toast');
  t.innerHTML = '<i class="fa-solid fa-circle-check" style="margin-right:6px;"></i>' + msg;
  t.className = 'toast show ' + type;
  setTimeout(() => t.className = 'toast', 3000);
}

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