  let users = [
    { id:1,  name:'Mark Francis Lampit',  email:'mark@email.com',   phone:'09329676767', orders:12, spent:85000,  registered:'Jan 15, 2025', active:true  },
    { id:2,  name:'John Dela Cruz',       email:'john@email.com',   phone:'09171234567', orders:5,  spent:32000,  registered:'Feb 2, 2025',  active:true  },
    { id:3,  name:'Maria Santos',         email:'maria@email.com',  phone:'09281234567', orders:8,  spent:67500,  registered:'Mar 10, 2025', active:true  },
    { id:4,  name:'Carlo Reyes',          email:'carlo@email.com',  phone:'09351234567', orders:3,  spent:25000,  registered:'Apr 5, 2025',  active:false },
    { id:5,  name:'Ana Lopez',            email:'ana@email.com',    phone:'09201234567', orders:15, spent:120000, registered:'Jan 20, 2025', active:true  },
    { id:6,  name:'Benjamin Torres',      email:'ben@email.com',    phone:'09161234567', orders:2,  spent:14400,  registered:'May 1, 2025',  active:true  },
    { id:7,  name:'Grace Villanueva',     email:'grace@email.com',  phone:'09091234567', orders:7,  spent:48500,  registered:'Mar 22, 2025', active:true  },
    { id:8,  name:'Rico Magno',           email:'rico@email.com',   phone:'09181234567', orders:1,  spent:4500,   registered:'May 10, 2025', active:false },
    { id:9,  name:'Liza Navarro',         email:'liza@email.com',   phone:'09271234567', orders:20, spent:215000, registered:'Dec 5, 2024',  active:true  },
    { id:10, name:'Jun Bautista',         email:'jun@email.com',    phone:'09151234567', orders:6,  spent:52000,  registered:'Feb 28, 2025', active:true  },
    { id:11, name:'Cathy Flores',         email:'cathy@email.com',  phone:'09321234567', orders:9,  spent:76500,  registered:'Jan 30, 2025', active:true  },
    { id:12, name:'Pedro Manansala',      email:'pedro@email.com',  phone:'09451234567', orders:0,  spent:0,      registered:'May 22, 2026', active:false },
  ];

  function initials(name) {
    return name.split(' ').map(w => w[0]).join('').slice(0, 2).toUpperCase();
  }

  function renderTable(data) {
    const tbody = document.getElementById('usersBody');
    tbody.innerHTML = data.map(u => `
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:12px;">
            <div class="cust-av-lg">${initials(u.name)}</div>
            <div>
              <div class="cust-name">${u.name}</div>
              <div class="cust-email">${u.email}</div>
            </div>
          </div>
        </td>
        <td style="font-size:12px;color:#6b7280;">${u.phone}</td>
        <td style="text-align:center;">
          <span style="background:#f3e8ff;color:#4E0B99;padding:4px 10px;border-radius:6px;font-size:12px;font-weight:700;">${u.orders}</span>
        </td>
        <td style="font-weight:700;color:#4E0B99;">₱${u.spent.toLocaleString()}</td>
        <td style="font-size:12px;color:#6b7280;">${u.registered}</td>
        <td>
          <div style="display:flex;align-items:center;gap:10px;">
            <button class="toggle-btn ${u.active ? 'on' : 'off'}" 
                    title="${u.active ? 'Disable account' : 'Enable account'}"
                    onclick="toggleUser(${u.id}, this)">
            </button>
            <span style="font-size:12px;font-weight:600;color:${u.active ? '#065f46' : '#9ca3af'};">
              ${u.active ? 'Active' : 'Inactive'}
            </span>
          </div>
        </td>
      </tr>
    `).join('');
  }

  function filterTable() {
    const q  = document.getElementById('searchInput').value.toLowerCase();
    const st = document.getElementById('statusFilter').value;
    const filtered = users.filter(u =>
      (u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q)) &&
      (st === '' || (st === 'active' ? u.active : !u.active))
    );
    renderTable(filtered);
  }

  function toggleUser(id, btn) {
    const u = users.find(x => x.id === id);
    if (!u) return;
    u.active = !u.active;
    btn.className = 'toggle-btn ' + (u.active ? 'on' : 'off');
    btn.title = u.active ? 'Disable account' : 'Enable account';
    const label = btn.nextElementSibling;
    label.style.color = u.active ? '#065f46' : '#9ca3af';
    label.textContent = u.active ? 'Active' : 'Inactive';
    showToast(`${u.name} is now ${u.active ? 'active' : 'inactive'}.`, u.active ? 'success' : '');
  }

  function showToast(msg, type = '') {
    const t = document.getElementById('toast');
    t.innerHTML = '<i class="fa-solid fa-circle-check" style="margin-right:6px;"></i>' + msg;
    t.className = 'toast show ' + type;
    setTimeout(() => t.className = 'toast', 3000);
  }

  renderTable(users);

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
  window.location.href = '../Admin/adminLog-in.html';
}