 let products = [
    { id:1, name:'GIGABYTE GeForce RTX 4090 D 24GB WINDFORCE', category:'GPU', price:5000, stock:14, sku:'GPU-001', image: 'added_IMG_Products/example1.png' },
    { id:2, name:'AMD Ryzen 9 7950X Processor', category:'CPU', price:28000, stock:8, sku:'CPU-001', image: 'added_IMG_Products/example2.png' }
  ];

  let editingId = null;
  let deletingId = null;
  let idCounter = products.length + 1;

  function stockColor(s) {
    if (s <= 3) return '#ef4444';
    if (s <= 10) return '#f59e0b';
    return '#10b981';
  }

  function stockPill(s) {
    if (s <= 3) return '<span class="pill pill-low">Low Stock</span>';
    if (s === 0) return '<span class="pill pill-cancelled">Out of Stock</span>';
    return '<span class="pill pill-active">In Stock</span>';
  }

  function renderTable(data) {
    const tbody = document.getElementById('productsBody');
    tbody.innerHTML = data.map(p => `
      <tr data-id="${p.id}">
        <td>
          <div style="display:flex;align-items:center;gap:12px;">
            <img src="${p.image || 'https://via.placeholder.com/52?text=No+Image'}" 
                 class="prod-img" 
                 onerror="this.onerror=null; this.src='https://via.placeholder.com/52?text=No+Image'">
            <div>
              <div class="prod-name">${p.name}</div>
              <div class="prod-sku">${p.sku}</div>
            </div>
          </div>
        </td>
        <td><span class="category-tag">${p.category}</span></td>
        <td style="font-weight:700;color:#4E0B99;">₱${p.price.toLocaleString()}</td>
        <td>
          <div class="stock-bar-wrap">
            <div class="stock-bar"><div class="stock-fill" style="width:${Math.min(100, (p.stock/30)*100)}%;background:${stockColor(p.stock)};"></div></div>
            <span class="stock-num">${p.stock}</span>
          </div>
        </td>
        <td>${stockPill(p.stock)}</td>
        <td>
          <div style="display:flex;gap:6px;">
            <button class="btn-icon" onclick="openEdit(${p.id})"><i class="fa-solid fa-pen"></i></button>
            <button class="btn-icon danger" onclick="openDelete(${p.id})"><i class="fa-solid fa-trash"></i></button>
          </div>
        </td>
      </tr>
    `).join('');
  }

  function previewImage(url) {
    const preview = document.getElementById('imagePreview');
    if (!url) {
      preview.innerHTML = '<small style="color:#9ca3af;">Preview will appear here</small>';
      return;
    }
    preview.innerHTML = `
      <img src="${url}" style="max-height:90px; border-radius:8px; border:1px solid #ddd;" 
           onerror="this.style.display='none'; this.parentElement.innerHTML='<small style=\'color:#ef4444\'>Cannot load image.<br>Check the path.</small>'">
    `;
  }

  function openModal() {
    editingId = null;
    document.getElementById('modalTitle').textContent = 'Add Product';
    document.getElementById('fName').value = '';
    document.getElementById('fImage').value = '';
    document.getElementById('fPrice').value = '';
    document.getElementById('fStock').value = '';
    // document.getElementById('fSku').value = '';
    document.getElementById('fCategory').value = 'GPU';
    document.getElementById('imagePreview').innerHTML = '';
    document.getElementById('productModal').classList.add('show');
  }

  function openEdit(id) {
    const p = products.find(x => x.id === id);
    if (!p) return;
    editingId = id;
    document.getElementById('modalTitle').textContent = 'Edit Product';
    document.getElementById('fName').value = p.name;
    document.getElementById('fImage').value = p.image || '';
    document.getElementById('fCategory').value = p.category;
    document.getElementById('fPrice').value = p.price;
    document.getElementById('fStock').value = p.stock;
    document.getElementById('fSku').value = p.sku;
    previewImage(p.image || '');
    document.getElementById('productModal').classList.add('show');
  }

  function saveProduct() {
    const name = document.getElementById('fName').value.trim();
    const image = document.getElementById('fImage').value.trim();
    const cat = document.getElementById('fCategory').value;
    const price = parseInt(document.getElementById('fPrice').value);
    const stock = parseInt(document.getElementById('fStock').value);
    const sku = document.getElementById('fSku').value.trim();

    if (!name || isNaN(price) || isNaN(stock)) {
      showToast('Please fill all required fields.', 'danger');
      return;
    }

    if (editingId) {
      const idx = products.findIndex(p => p.id === editingId);
      products[idx] = { ...products[idx], name, image, category: cat, price, stock, sku };
      showToast('Product updated!', 'success');
    } else {
      products.push({ id: idCounter++, name, image, category: cat, price, stock, sku });
      showToast('Product added!', 'success');
    }

    closeModal();
    renderTable(products);
  }

  function closeModal() {
    document.getElementById('productModal').classList.remove('show');
  }

  function openDelete(id) {
    deletingId = id;
    const p = products.find(x => x.id === id);
    document.getElementById('deleteProductName').textContent = p.name;
    document.getElementById('deleteModal').classList.add('show');
  }

  function closeDelete() {
    document.getElementById('deleteModal').classList.remove('show');
  }

  function confirmDelete() {
    products = products.filter(p => p.id !== deletingId);
    closeDelete();
    renderTable(products);
    showToast('Product deleted.', 'danger');
  }

  function showToast(msg, type = '') {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className = `toast show ${type}`;
    setTimeout(() => t.className = 'toast', 3000);
  }

  function filterTable() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const cat = document.getElementById('categoryFilter').value;
    const filtered = products.filter(p => 
      (p.name.toLowerCase().includes(q) || p.sku.toLowerCase().includes(q)) &&
      (!cat || p.category === cat)
    );
    renderTable(filtered);
  }

  // Initial render
  renderTable(products);
  
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