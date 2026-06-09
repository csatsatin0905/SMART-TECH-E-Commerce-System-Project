<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shop - Smart Tech</title>
  <link rel="stylesheet" href="Assets/CSS/navBar.css">
  <link rel="stylesheet" href="Assets/CSS/shop-css.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="Assets/CSS/notifications.css">
  <script src="Assets/JavaScript/script.js" defer></script>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar">
    <div class="nav-container">
      <h1 class="logo">Smart Tech</h1>

      <div class="search-container">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
        <input type="text" id="searchInput" onkeyup="searchProduct()" placeholder="Search category"
          class="search-input">
      </div>

      <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="shop.php" class="active">Shop</a>
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

  <!-- Breadcrumb -->
  <div class="breadcrumb">
    <a href="home.php">HOME</a>
    <span class="separator">/</span>
    <span class="current">SHOP</span>
  </div>

  <!-- Categories Section -->
  <section class="categories-section">
    <h2 class="section-title">Categories</h2>

    <div class="categories-grid">

      <a href="Categories/categories.php?category_id=1" class="category-card">
        <img src="Assets/pictures/accessories.png" alt="Accessories">
        <p>Accessories</p>
      </a>

      <a href="Categories/categories.php?category_id=2" class="category-card">
        <img src="Assets/pictures/cpu-home.png" alt="Case">
        <p>Case</p>
      </a>

      <a href="Categories/categories.php?category_id=3" class="category-card">
        <img src="Assets/pictures/cooling-system.png" alt="Cooling">
        <p>Cooling System</p>
      </a>

      <a href="Categories/categories.php?category_id=4" class="category-card">
        <img src="Assets/pictures/CPU.png" alt="CPU">
        <p>CPU (Processor)</p>
      </a>

      <a href="Categories/categories.php?category_id=5" class="category-card">
        <img src="Assets/pictures/GPU.png" alt="GPU">
        <p>GPU (Graphics Card)</p>
      </a>

      <a href="Categories/categories.php?category_id=6" class="category-card">
        <img src="Assets/pictures/motherBoard.png" alt="Motherboard">
        <p>Motherboard</p>
      </a>

      <a href="Categories/categories.php?category_id=7" class="category-card">
        <img src="Assets/pictures/PSU.png" alt="PSU">
        <p>PSU (Power Supply)</p>
      </a>

      <a href="Categories/categories.php?category_id=8" class="category-card">
        <img src="Assets/pictures/RAM.png" alt="RAM">
        <p>RAM (Memory)</p>
      </a>

      <a href="Categories/categories.php?category_id=9" class="category-card">
        <img src="Assets/pictures/storage.png" alt="Storage">
        <p>Storage (SSD/HDD)</p>
      </a>

    </div>

  </section>

    <script>
        let dots = "";
    </script>  
<script src="Assets/JavaScript/notifications.js"></script>


</body>

</html>