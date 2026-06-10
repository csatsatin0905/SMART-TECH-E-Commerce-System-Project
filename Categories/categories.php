<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  // relative path back to login.php in parent folder
  header("Location: ../User/log-in.php");
  exit;
}

require_once '../Database/runQuery.php';
$categoryID = $_GET['category_id'] ?? 1; // Default to 1 if not provided
$sql = "SELECT * FROM products WHERE category_id = ? AND stock > 0 AND is_deleted = 0;";
$result = runQuery($pdo, $sql, [$categoryID], true);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Tech Products</title>

  <link rel="stylesheet" href="../Assets/CSS/navBar.css">
  <link rel="stylesheet" href="../Assets/CSS/category.css">
  <link rel="stylesheet" href="../node_modules/@fortawesome/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="../Assets/CSS/notifications.css">
  <script src="../Assets/JavaScript/script.js" defer></script>
  <script src="../Assets/JavaScript/product.js" defer></script>
  <link rel="stylesheet" href="../Assets/JavaScript/SweetAlert2/sweetalert2.min.css">
  <script src="../Assets/JavaScript/SweetAlert2/sweetalert2.all.min.js"></script>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar">
    <div class="nav-container">
      <h1 class="logo">Smart Tech</h1>

      <div class="search-container">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
        <input type="text" id="searchInput" onkeyup="searchProduct()" placeholder="Search Products..."
          class="search-input">
      </div>

      <div class="nav-links">
        <a href="../home.php">Home</a>
        <a href="../shop.php">Shop</a>
        <a href="../order.php">Order</a>
        <a href="../cart.php">Cart</a>
        <a href="../User/profile.php">
          <div class="profile-icon">
            <i class="fa-solid fa-user"></i>
          </div>
        </a>
        <?php include '../reusable-notif.php'; ?>
      </div>
    </div>
  </nav>

  <!-- Breadcrumb -->
  <div class="breadcrumb">
    <a href="../home.php">HOME</a>
    <span class="separator">/</span>
    <a href="../shop.php">SHOP</a>
    <span class="separator">/</span>
    <span class="current">
      <?php
      // Display category name based on category_id
      $categoryNames = [
        1 => 'Accessories',
        2 => 'Case',
        3 => 'Cooling System',
        4 => 'Central Processing Unit (CPU)',
        5 => 'Graphics Processing Unit (GPU)',
        6 => 'Motherboard',
        7 => 'Power Supply Unit (PSU)',
        8 => 'Random Access Memory (RAM)',
        9 => 'Storage'
      ];
      echo $categoryNames[$categoryID] ?? 'Category';
      ?>
    </span>
  </div>

  <div class="products-page">
    <!-- Sidebar -->
    <div class="sidebar">
      <h3 class="filter-title">Price Range</h3>

      <div class="price-range-container">
        <div class="input-group">
          <label>Price Minimum</label>
          <div class="input-wrapper">
            <span class="peso">₱</span>
            <input type="number" id="minPrice" placeholder="0" value="0">
          </div>
        </div>

        <div class="input-group">
          <label>Price Maximum</label>
          <div class="input-wrapper">
            <span class="peso">₱</span>
            <input type="number" id="maxPrice" placeholder="50000" value="50000">
          </div>
        </div>

        <div class="filter-buttons">
          <button class="reset-btn" id="resetBtn">
            <i class="fa-solid fa-rotate-left"></i> Reset
          </button>
          <button class="set-btn" id="setBtn">
            Set Price Range
          </button>
        </div>
      </div>
    </div>

    <!-- Products Content -->
    <div class="products-content">
      <h2 class="page-title">
        <?php
        echo $categoryNames[$categoryID] ?? 'Category';
        ?>
      </h2>

      <div class="products-grid">

        <?php foreach ($result as $product): ?>
          <div class="product-card">
            <a href="../product.php?product_id=<?= $product['product_id'] ?>" style="text-decoration:none;color:inherit;">
              <div class="product-image">
                <img src="../<?= $product['image'] ?>" alt="<?= $product['product_name'] ?>"
                  onerror="this.onerror=null; this.src='https://via.placeholder.com/300?text=No+Image'  ">
              </div>
              <div class="product-info">
                <h3><?= $product['product_name'] ?></h3>
                <p class="price">₱<?= number_format($product['price'], 2) ?></p>
              </div>
            </a>
            <div class="product-actions" style="padding: 0 20px 20px;">
              <button class="add-to-cart-btn" onclick="addToCartProduct(<?= $product['product_id'] ?>)"
                title="Add to Cart">
                <i class="fa-solid fa-cart-plus"></i>
              </button>
              <span class="buy-now-btn" onclick="buyNow(<?= $product['product_id'] ?>)">Buy Now</span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Added to Cart Popup -->
  <div id="addedToCartPopup" class="added-popup">
    <div class="popup-content">
      <div class="check-circle">
        <i class="fa-solid fa-check"></i>
      </div>
      <h3>ADDED TO CART</h3>
    </div>
  </div>

  <script>
    async function buyNow(productID) {
      await addToCart(productID); // Add to cart first
      setTimeout(() => {
        window.location.href = '../cart.php';
      }, 500);
    }

    async function addToCartProduct(productID) {
      await addToCart(productID); // Add to cart first
      showAddedPopup(); // Show popup immediately
    }

    async function addToCart(productID) {
      const formData = new FormData();
      formData.append('product_id', productID);
      formData.append('quantity', 1);

      try {
        const response = await fetch('../Actions/Product/add-to-cart.php', {
          method: 'POST',
          body: formData
        });
        const result = await response.json();
      } catch (error) {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Failed to add product to cart. Please try again.',
        });
      }
    }

    // Price range filter
    document.getElementById('setBtn').addEventListener('click', () => {
      const min = parseInt(document.getElementById('minPrice').value) || 0;
      const max = parseInt(document.getElementById('maxPrice').value) || 999999;
      document.querySelectorAll('.product-card').forEach(card => {
        const priceText = card.querySelector('.price').textContent.replace(/[₱,]/g, '');
        const price = parseInt(priceText);
        card.style.display = (price >= min && price <= max) ? 'flex' : 'none';
      });
    });

    document.getElementById('resetBtn').addEventListener('click', () => {
      document.getElementById('minPrice').value = 0;
      document.getElementById('maxPrice').value = 50000;
      document.querySelectorAll('.product-card').forEach(card => card.style.display = 'flex');
    });
  </script>

  <script>
    let dots = "../";
  </script>
  <script src="../Assets/JavaScript/notifications.js"></script>

</body>

</html>