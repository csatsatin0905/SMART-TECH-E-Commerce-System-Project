<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  // relative path back to login.php in parent folder
  header("Location: User/log-in.php");
  exit;
}
require_once 'Database/runQuery.php';
$sql = "SELECT c.cart_id, c.quantity, p.product_id, p.product_name, p.price, p.stock, p.image FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = ? ORDER BY c.created_at DESC;";
$cartItems = runQuery($pdo, $sql, [$_SESSION['user_id']], true);

$sql = "SELECT a.* FROM addresses a JOIN users u ON a.address_id = u.current_address_id WHERE u.user_id = ?;";
$address = runQuery($pdo, $sql, [$_SESSION['user_id']])->fetch();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cart - Smart Tech</title>
  <link rel="stylesheet" href="Assets/CSS/navBar.css">
  <link rel="stylesheet" href="Assets/CSS/cart-css.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="Assets/CSS/notifications.css">
  <script src="Assets/JavaScript/script.js" defer></script>
  <script src="Assets/JavaScript/cart.js" defer></script>
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
        <a href="cart.php" class="active">Cart</a>
        <a href="User/profile.php">
          <div class="profile-icon">
            <i class="fa-solid fa-user"></i>
          </div>
        </a>
        <?php include 'reusable-notif.php'; ?>
      </div>
    </div>
  </nav>

  <div class="cart-page">
    <h1 class="page-title">Cart</h1>

    <!-- Address Bar + Checkout Button (Exact Figma Layout) -->
    <div class="address-checkout-container">
      <!-- Address Bar -->
      <div class="address-bar">
        <div class="address-content">
          <i class="fa-solid fa-location-dot location-icon"></i>
          <div class="address-details">
            <div class="user-info">
              <strong><?= htmlspecialchars($address['full_name'] ?? 'No name provided') ?></strong>
              <span><?= htmlspecialchars($address['phone'] ?? 'No phone provided') ?></span>
            </div>
            <p class="full-address">
              <?= htmlspecialchars($address['address_line'] ?? 'No address provided') . ", " . htmlspecialchars($address['city'] ?? 'No city provided') . ", " . htmlspecialchars($address['province'] ?? 'No province provided') ?>
            </p>
          </div>
        </div>
        <a href="User/address.php" class="change-address-btn">Change address</a>
      </div>

      <!-- Check Out Button -->
      <a href="buy.php" class="checkout-btn">Check out</a>
    </div>

    <!-- Cart Items -->
    <div class="cart-items">
      <table class="cart-table" style="text-align: center;">
        <thead>
          <tr style="text-align: center;">
            <th width="40px"></th>
            <th>Product</th>
            <th>Unit Price</th>
            <th>Stock</th>
            <th>Quantity</th>
            <th>Total Price</th>
          </tr>
        </thead>

        <!-- ETO YUNG SA ITEM CARTS -->
        <tbody>
          <?php foreach ($cartItems as $item): ?>
            <tr id="<?= $item['product_id'] ?>">
              <td onclick="deleteCart(<?= $item['cart_id'] ?>)" style="text-align: center; padding:30px"><i
                  class="fa-solid fa-trash" style="color: #dc3545; cursor: pointer;" onclick="removeFromCart(this)"></i>
              </td>
              <td class="product-cell" style="padding-left: 0">
                <img src="<?= $item['image'] ?>" alt="<?= $item['product_name'] ?>" class="product-img">
                <strong><?= $item['product_name'] ?></strong>
              </td>
              <td class="unit-price">₱<?= number_format($item['price'], 2) ?></td>
              <td class="stock-status"><?= $item['stock'] > 0 ? 'In Stock' : 'Out of Stock' ?></td>
              <td class="quantity">
                <div class="quantity-wrapper" style="display:flex; justify-content: center;">
                  <button class="qty-btn" onclick="changeQty(this, -1, <?= $item['stock'] ?>)" <?= $item['stock'] <= 0 ? 'disabled' : '' ?>>–</button>
                  <span class="qty-value"><?= $item['quantity'] ?></span>
                  <button class="qty-btn" onclick="changeQty(this, 1, <?= $item['stock'] ?>)" <?= $item['stock'] <= 0 ? 'disabled' : '' ?>>+</button>
                </div>
              </td>
              <td class="total-price">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>

      </table>
    </div>
  </div>

  <script>
    let dots = "";
  </script>
  <script src="Assets/JavaScript/notifications.js"></script>


</body>

</html>