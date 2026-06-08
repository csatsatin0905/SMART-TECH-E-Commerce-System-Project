<?php
session_start();
require_once 'Database/runQuery.php';
$productID = $_GET['product_id'] ?? 1; // Default to 1 if not provided
$sql = "SELECT * FROM products WHERE product_id = ?;";
$product = runQuery($pdo, $sql, [$productID])->fetch();

$sql = "SELECT r.*, u.first_name, u.last_name FROM reviews r JOIN users u ON r.user_id = u.user_id WHERE r.product_id = ?;";
$reviews = runQuery($pdo, $sql, [$productID], true);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $product['product_name'] ?> - Smart Tech</title>
  <link rel="stylesheet" href="Assets/CSS/extra-home.css">
  <link rel="stylesheet" href="Assets/CSS/navBar.css">
  <link rel="stylesheet" href="Assets/CSS/product-css.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="Assets/JavaScript/script.js" defer></script>
  <script src="Assets/JavaScript/product.js" defer></script>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar">
    <div class="nav-container">
      <h1 class="logo">Smart Tech</h1>

      <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="shop.php">Shop</a>
        <a href="order.php">Order</a>
        <a href="cart.php">Cart</a>
        <a href="User/profile.php">
          <div class="profile-icon">
            <i class="fa-solid fa-user"></i>
          </div>
        </a>
      </div>
    </div>
  </nav>

  <div class="product-page">
    <div class="product-container">

      <!-- Left: Image -->
      <div class="product-image-section">
        <img src="<?= $product['image'] ?>" alt="<?= $product['product_name'] ?>" class="main-image">
      </div>

      <!-- Right: Product Info -->
      <div class="product-info-section">
        <h1 class="product-title"><?= $product['product_name'] ?></h1>

        <span class="price">₱<?= number_format($product['price'], 2) ?></span>

        <p class="short-description">
          <?= $product['description'] ?>
        </p>

        <!-- Options -->
        <div class="options">
          <div class="option-group">
            <label>Quantity</label>
            <div class="quantity-wrapper">
              <button class="qty-btn" onclick="changeQty(-1)">–</button>
              <span id="quantity" class="qty-value">1</span>
              <button class="qty-btn" onclick="changeQty(1)">+</button>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
          <a class="buy-now-btn" onclick="buyNow()">Buy Now</a>
          <a class="add-to-cart-btn" onclick="addToCartProduct()">
            <i class="fa-solid fa-cart-plus"></i> Add to Cart
          </a>
        </div>
      </div>
    </div>

    <!-- Reviews -->
    <div class="reviews-section">
      <h2>Customer Reviews</h2>

      <?php foreach ($reviews as $review): ?>
        <div class="review-card">
          <div class="review-header">
            <div class="avatar"><?= substr($review['first_name'], 0, 1) . substr($review['last_name'], 0, 1) ?></div>
            <div>
              <strong class="reviewer-name"><?= $review['first_name'] ?>   <?= $review['last_name'] ?></strong>
            </div>
          </div>
          <p class="review-text"><?= $review['comment'] ?></p>
        </div>
      <?php endforeach; ?>
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
    async function buyNow() {
      await addToCart(); // Add to cart first
      setTimeout(() => {
        window.location.href = 'cart.php';
      }, 500);
    }

    async function addToCartProduct() {
      await addToCart(); // Add to cart first
      showAddedPopup(); // Show popup immediately
    }

    async function addToCart() {
      const formData = new FormData();
      formData.append('product_id', <?= $productID ?>);
      formData.append('quantity', document.getElementById('quantity').textContent);

      try {
        const response = await fetch('Actions/Product/add-to-cart.php', {
          method: 'POST',
          body: formData
        });
        const result = await response.json();
      } catch (error) {
        console.error('Error adding to cart:', error); //will place sweetalert here
      }
    }

  </script>


</body>

</html>