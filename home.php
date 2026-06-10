<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  // relative path back to login.php in parent folder
  header("Location: User/log-in.php");
  exit;
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Tech</title>
  <link rel="stylesheet" href="Assets/CSS/navBar.css">
  <link rel="stylesheet" href="Assets/CSS/home-css.css">
  <link rel="stylesheet" href="Assets/CSS/notifications.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <script src="Assets/JavaScript/script.js" defer></script>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar">
    <div class="nav-container">
      <h1 class="logo">Smart Tech</h1>

      <div class="nav-links">
        <a href="home.php" class="active">Home</a>
        <a href="shop.php">Shop</a>
        <a href="order.php">Order</a>
        <a href="cart.php">Cart</a>
        <a href="User/profile.php">
          <div class="profile-icon">
            <i class="fa-solid fa-user"></i>
          </div>
        </a>

        <?php include 'reusable-notif.php'; ?>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <div class="hero-text">
        <h1>Premium Parts.<br><span class="highlight">Powerful Performance.</span></h1>
        <p>High Performance PC components and stunning prebuilt system for gamers, creators, and pros.</p>

        <div class="hero-buttons">
          <a href="shop.php" class="btn primary-btn">Shop all parts →</a>
          <!--<a href="#" class="btn secondary-btn">Shop Pre-Built PCs →</a>-->
        </div>
      </div>

      <div class="hero-image">
        <img src="Assets/pictures/cpu-home.png" alt="Gaming PC" loading="lazy">
      </div>
    </div>
  </section>

  <!-- Trust Bar -->
  <div class="trust-bar">
    <div class="trust-item">
      <i class="fa-solid fa-truck"></i>
      <div class="trust-text">
        <strong>Free & Fast Shipping</strong>
        <span><br>On Orders over ₱500</span>
      </div>
    </div>

    <div class="trust-item">
      <i class="fa-solid fa-shield-halved"></i>
      <div class="trust-text">
        <strong>Secure Payments</strong>
        <span><br>100% Secure Checkouts</span>
      </div>
    </div>

    <div class="trust-item">
      <i class="fa-solid fa-arrow-rotate-left"></i>
      <div class="trust-text">
        <strong>Easy Returns</strong>
        <span><br>30 days return policy</span>
      </div>
    </div>

    <div class="trust-item">
      <i class="fa-solid fa-headset"></i>
      <div class="trust-text">
        <strong>Expert Support</strong>
        <span><br>We're here to help</span>
      </div>
    </div>

  </div>

  <!-- Shop by Categories -->
  <section class="section">

    <div class="section-header">
      <h2>Shop by Categories</h2>
      <a href="shop.php" class="view-all">View All Category <i class="fa-solid fa-arrow-right"></i></a>
    </div>

    <div class="category-featured-grid">
      <a href="Categories/categories.php?category_id=5" class="featured-card">
        <img src="Assets/pictures/GPU.png" alt="GPU">
        <h3>GPU's</h3>
        <p>ULTRA gaming<br>performance</p>
        <span class="shop-now">Shop now →</span>
      </a>

      <a href="Categories/categories.php?category_id=4" class="featured-card">
        <img src="Assets/pictures/CPU.png" alt="CPU">
        <h3>CPU's</h3>
        <p>Top performance <br>processors</p>
        <span class="shop-now">Shop now →</span>
      </a>

      <a href="Categories/categories.php?category_id=7" class="featured-card">
        <img src="Assets/pictures/PSU.png" alt="PSU">
        <h3>PSU</h3>
        <p>Stable power<br>Maximum safety</p>
        <span class="shop-now">Shop now →</span>
      </a>

      <a href="Categories/categories.php?category_id=8" class="featured-card">
        <img src="Assets/pictures/RAM.png" alt="RAM">
        <h3>RAM</h3>
        <p>Faster Load times<br>smooth gameplay</p>
        <span class="shop-now">Shop now →</span>
      </a>
    </div>

  </section>

  <!--Prebuilt Gaming PCs 
  <section class="section">
    <div class="section-header">
      <h2>Prebuilt Gaming PCs</h2>
      <a href="#" class="view-all">View All Prebuilt PC's <i class="fa-solid fa-arrow-right"></i></a>
    </div>
    <div class="cards-grid">
      <div class="card"></div>
      <div class="card"></div>
      <div class="card"></div>
      <div class="card"></div>
      <div class="card"></div>
    </div>
  </section>
  -->

  <script>
    let dots = "";
  </script>
  <script src="Assets/JavaScript/notifications.js"></script>

</body>

</html>