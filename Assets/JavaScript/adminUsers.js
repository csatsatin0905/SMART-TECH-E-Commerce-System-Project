let users = [];
let total_customers = 0;
let active_customers = 0;
let inactive_customers = 0;
let new_this_month = 0;

function initials(name) {
  return name.split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase();
}

function renderTable(data, source = '') {
  const tbody = document.getElementById('usersBody');
  tbody.innerHTML = data.map(u => `
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:12px;">
            <div class="cust-av-lg">${initials(u.full_name)}</div>
            <div>
              <div class="cust-name">${u.full_name}</div>
              <div class="cust-email">${u.email}</div>
            </div>
          </div>
        </td>
        <td style="font-size:12px;color:#6b7280;">${u.phone}</td>
        <td style="text-align:center;">
          <span style="background:#f3e8ff;color:#4E0B99;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:700;">${u.total_orders}</span>
        </td>
        <td style="font-weight:700;color:#4E0B99;">₱${u.total_spent.toLocaleString()}</td>
        <td style="font-size:12px;color:#6b7280;">${new Date(u.created_at.replace(' ', 'T')).toLocaleDateString('en-US', {
    month: 'long',
    day: 'numeric',
    year: 'numeric',
    hour: 'numeric',
    minute: '2-digit',
    hour12: true
  })}</td>
        <td>
          <div style="display:flex;align-items:center;gap:10px;">
            <button class="toggle-btn ${u.is_active ? 'on' : 'off'}" 
                    title="${u.is_active ? 'Disable account' : 'Enable account'}"
                    onclick="toggleUser(${u.user_id}, this)">
            </button>
            <span style="font-size:12px;font-weight:600;color:${u.is_active ? '#065f46' : '#9ca3af'};">
              ${u.is_active ? 'Active' : 'Inactive'}
            </span>
          </div>
        </td>
      </tr>
    `).join('');
  if (source === 'filtering') return; // Don't update stats when just filtering
  total_customers = data.length;
  active_customers = data.filter(u => u.is_active).length;
  inactive_customers = total_customers - active_customers;
  const now = new Date();
  new_this_month = data.filter(u => {
    const created = new Date(u.created_at.replace(' ', 'T'));
    return created.getMonth() === now.getMonth() && created.getFullYear() === now.getFullYear();
  }).length;
  document.getElementById('totalCustomers').textContent = total_customers;
  document.getElementById('activeCustomers').textContent = active_customers;
  document.getElementById('inactiveCustomers').textContent = inactive_customers;
  document.getElementById('newThisMonth').textContent = new_this_month;
}

function filterTable() {
  const q = document.getElementById('searchInput').value.toLowerCase();
  const st = document.getElementById('statusFilter').value;
  const filtered = users.filter(u =>
    (u.full_name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q)) &&
    (st === '' || (st === 'active' ? u.is_active : !u.is_active))
  );
  renderTable(filtered, 'filtering');
}

async function toggleUser(id, btn) {
  const u = users.find(x => x.user_id === id);
  if (!u) return;
  u.is_active = !u.is_active;
  btn.className = 'toggle-btn ' + (u.is_active ? 'on' : 'off');
  btn.title = u.is_active ? 'Disable account' : 'Enable account';
  const label = btn.nextElementSibling;
  label.style.color = u.is_active ? '#065f46' : '#9ca3af';
  label.textContent = u.is_active ? 'Active' : 'Inactive';
  showToast(`${u.full_name} is now ${u.is_active ? 'active' : 'inactive'}.`, u.is_active ? 'success' : '');

  try {
    const formData = new FormData();
    formData.append('userId', id);
    formData.append('newStatus', u.is_active ? 'true' : 'false');
    const response = await fetch('../Actions/Admin_Customers/update_user_status.php', {
      method: 'POST',
      body: formData
    });
    const result = await response.json();
    if (!result.success) {
      showToast('Error updating user status: ' + result.error, 'error');
    }
    fetchCustomers(); // Refresh the user list to reflect any changes
  } catch (error) {
    showToast('Network error: ' + error.message, 'error');
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