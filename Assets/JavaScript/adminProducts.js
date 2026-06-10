let products = [];
let total_products = 0;
let total_in_stock = 0;
let total_low_stock = 0;
let total_out_of_stock = 0;

async function fetchTableData() {
  console.log('Fetching table data...');
  try {
    const response = await fetch('../Actions/Admin_Products/fetch-table-data.php');
    if (!response.ok) throw new Error('Network response was not ok');
    const data = await response.json();
    if (data.success === false) throw new Error(data.message || 'Failed to fetch data');
    products = data.data;
    renderTable(products);
  } catch (error) {
    console.error('Error fetching table data:', error);
  }
}

let editingId = null;
let deletingId = null;
let idCounter = products.length + 1;

function stockColor(s) {
  if (s <= 3) return '#ef4444';
  if (s <= 10) return '#f59e0b';
  return '#10b981';
}

function stockPill(s) {
  if (s <= 10 && s > 0) return '<span class="pill pill-low">Low Stock</span>';
  if (s === 0) return '<span class="pill pill-cancelled">Out of Stock</span>';
  return '<span class="pill pill-active">In Stock</span>';
}

function renderTable(data) {
  const tbody = document.getElementById('productsBody');
  tbody.innerHTML = '';
  //for loop to generate table rows from data
  tbody.innerHTML = data.map(p => `
      <tr data-id="${p.product_id}">
        <td>
          <div style="display:flex;align-items:center;gap:12px;">
            <img src="../${p.image || 'https://via.placeholder.com/52?text=No+Image'}" 
                 class="prod-img" 
                 onerror="this.onerror=null; this.src='https://via.placeholder.com/52?text=No+Image'">
            <div>
              <div class="prod-name">${p.product_name}</div>
            </div>
          </div>
        </td>
        <td><span class="category-tag">${p.category}</span></td>
        <td style="font-weight:700;color:#4E0B99;">₱${p.price.toLocaleString()}</td>
        <td>
          <div class="stock-bar-wrap">
            <div class="stock-bar"><div class="stock-fill" style="width:${Math.min(100, (p.stock / 30) * 100)}%;background:${stockColor(p.stock)};"></div></div>
            <span class="stock-num">${p.stock}</span>
          </div>
        </td>
        <td>${stockPill(p.stock)}</td>
        <td>
          <div style="display:flex;gap:6px;">
            <button class="btn-icon" onclick="openEdit(${p.product_id})"><i class="fa-solid fa-pen"></i></button>
            <button class="btn-icon danger" onclick="openDelete(${p.product_id})"><i class="fa-solid fa-trash"></i></button>
          </div>
        </td>
      </tr>
    `).join('');
  total_products = products.length;
  total_in_stock = products.filter(p => p.stock > 0).length;
  total_low_stock = products.filter(p => p.stock > 0 && p.stock <= 10).length;
  total_out_of_stock = products.filter(p => p.stock === 0).length;
  document.getElementById('totalProducts').textContent = total_products;
  document.getElementById('inStock').textContent = total_in_stock;
  document.getElementById('lowStock').textContent = total_low_stock;
  document.getElementById('outOfStock').textContent = total_out_of_stock;
}

function previewImage(url) {
  const preview = document.getElementById('imagePreview');
  if (!url) {
    preview.innerHTML = '<small style="color:#9ca3af;">Preview will appear here</small>';
    return;
  }
  preview.innerHTML = `
      <img src="${url}" style="max-height:90px; border-radius:8px; border:1px solid #ddd;">
    `;
}

const productImageInput = document.getElementById('productImage');
productImageInput.addEventListener('change', function () {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      previewImage(e.target.result);
    };
    reader.readAsDataURL(file);
  }
});

function openModal() {
  editingId = null;
  document.getElementById('modalTitle').textContent = 'Add Product';
  document.getElementById('fName').value = '';
  document.getElementById('fPrice').value = '';
  document.getElementById('fStock').value = '';
  document.getElementById('fCategory').value = 'GPU';
  document.getElementById('imagePreview').innerHTML = '';
  document.getElementById('productModal').classList.add('show');
}

function openEdit(id) {
  //find the product by id and populate the form
  const p = products.find(x => x.product_id === id);
  if (!p) return;
  editingId = id;
  document.getElementById('modalTitle').textContent = 'Edit Product';
  document.getElementById('fName').value = p.product_name;
  document.getElementById('fCategory').value = p.category_id;
  document.getElementById('fPrice').value = p.price;
  document.getElementById('fStock').value = p.stock;
  document.getElementById('fSpecs').value = p.description || '';
  previewImage("../" + (p.image || ''));
  document.getElementById('productModal').classList.add('show');


}

async function saveProduct() {
  const name = document.getElementById('fName').value.trim();
  const file = document.getElementById('productImage').files[0];
  const cat = document.getElementById('fCategory').value;
  const price = parseInt(document.getElementById('fPrice').value);
  const stock = parseInt(document.getElementById('fStock').value);
  const desc = document.getElementById('fSpecs').value;

  if (!name || isNaN(price) || isNaN(stock)) {
    showToast('Please fill all required fields.', 'danger');
    return;
  }

  if (editingId) {
    //for updating existing product
    const formData = new FormData();
    formData.append('product_id', editingId);
    formData.append('name', name);
    formData.append('category_id', cat);
    formData.append('price', price);
    formData.append('stock', stock);
    formData.append('desc', desc);
    if (file) formData.append('image', file);
    try {
      const response = await fetch('../Actions/Admin_Products/edit-product.php', {
        method: 'POST',
        body: formData
      });
      if (!response.ok) throw new Error('Network response was not ok');
      const data = await response.json();
      if (data.success === false) throw new Error(data.message || 'Failed to update product');
    } catch (err) {
      showToast('Error updating product.', 'danger');
    }
    showToast('Product updated!', 'success');
  } else {
    //for saving new product
    const formData = new FormData();
    formData.append('name', name);
    formData.append('category_id', cat);
    formData.append('price', price);
    formData.append('stock', stock);
    formData.append('desc', desc);
    if (file) formData.append('image', file);
    try {
      const response = await fetch('../Actions/Admin_Products/add-product.php', {
        method: 'POST',
        body: formData
      });
      if (!response.ok) throw new Error('Network response was not ok');
      const data = await response.json();
      if (data.success === false) throw new Error(data.message || 'Failed to add product');
    } catch (err) {
      showToast('Error adding product.', 'danger');
    }
    showToast('Product added!', 'success');
  }

  await fetchTableData(); // Refresh the table after updating or saving
  closeModal();
}

function closeModal() {
  document.getElementById('productModal').classList.remove('show');
}

function openDelete(id) {
  deletingId = id;
  const p = products.find(x => x.product_id === id);
  document.getElementById('deleteProductName').textContent = p.product_name;
  document.getElementById('deleteModal').classList.add('show');
}

function closeDelete() {
  document.getElementById('deleteModal').classList.remove('show');
}

async function confirmDelete() {
  if (!deletingId) return;
  const formData = new FormData();
  formData.append('product_id', deletingId);
  try {
    const response = await fetch('../Actions/Admin_Products/delete-product.php', {
      method: 'POST',
      body: formData
    });
    if (!response.ok) throw new Error('Network response was not ok');
    const data = await response.json();
    if (data.success === false) throw new Error(data.message || 'Failed to delete product');
  } catch (err) {
    showToast('Error deleting product.', 'danger');
  }
  closeDelete();
  await fetchTableData(); // Refresh the table after adding
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
    (p.product_name.toLowerCase().includes(q)) &&
    (!cat || p.category === cat)
  );
  renderTable(filtered);
}

// Initial render
fetchTableData();

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