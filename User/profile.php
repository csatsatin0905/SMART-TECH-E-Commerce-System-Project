<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // relative path back to login.php in parent folder
    header("Location: log-in.php");
    exit;
}
require_once '../Database/runQuery.php';
$sql = "SELECT u.first_name, u.last_name, a.* FROM users u JOIN addresses a ON u.current_address_id = a.address_id WHERE u.user_id = ?";
$resulta = runQuery($pdo, $sql, [$_SESSION['user_id']]);
$user = $resulta->fetch();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Smart Tech</title>

    <link rel="stylesheet" href="../Assets/CSS/navBar.css">
    <link rel="stylesheet" href="../Assets/CSS/dashboard.css">
    <link rel="stylesheet" href="../Assets/CSS/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../Assets/CSS/notifications.css">
    <script src="../Assets/JavaScript/script.js" defer></script>
    <script src="../Assets/JavaScript/profile.js" defer></script>

</head>

<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <h1 class="logo">Smart Tech</h1>

            <div class="search-container">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="searchInput" onkeyup="searchProduct()" placeholder="Search" class="search-input">
            </div>

            <div class="nav-links">
                <a href="../home.php">Home</a>
                <a href="../shop.php">Shop</a>
                <a href="../order.php">Order</a>
                <a href="../cart.php">Cart</a>
                <a href="profile.php" class="active">
                    <div class="profile-icon">
                        <i class="fa-solid fa-user"></i>
                    </div>
                </a>
                <?php include '../reusable-notif.php'; ?>
            </div>
        </div>
    </nav>

    <div class="dashboard">

        <aside class="sidebar">

            <div class="user-box">
                <i class="fa-solid fa-circle-user"></i>
                <span id="sidebarName"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></span>
            </div>

            <a href="profile.php" class="active">
                <i class="fa-solid fa-user"></i>
                My Profile
            </a>

            <a href="address.php">
                <i class="fa-solid fa-location-dot"></i>
                Address
            </a>
            <a href="../home.php" class="home-btn">
                <i class="fa-solid fa-house"></i>
                Back to Home
            </a>

            <a href="#" onclick="logoutUser()">
                <i class="fa-solid fa-right-from-bracket"></i>
                Log Out
            </a>

        </aside>

        <main class="content">

            <div class="welcome-box">
                <h1 id="welcomeText">Welcome back,
                    <?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>!
                </h1>
                <p>Manage your account information</p>
            </div>

            <div class="profile-card">

                <div class="profile-left">
                    <i class="fa-solid fa-circle-user big-icon"></i>
                    <h2 id="profileName"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h2>
                </div>


            </div>

            <div class="info-card">

                <div class="info-row">
                    <span>Name:</span>
                    <strong
                        id="displayName"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></strong>
                </div>

                <div class="info-row">
                    <span>Phone Number:</span>
                    <strong id="displayPhone"><?= htmlspecialchars($user['phone']) ?></strong>
                </div>

                <div class="info-row">
                    <span>Address:</span>
                    <strong id="displayAddress">
                        <?= htmlspecialchars($user['address_line'] . ', ' . $user['city'] . ', ' . $user['province'] . ', ' . $user['postal_code']) ?>
                    </strong>
                </div>

            </div>

        </main>
    </div>

    <div class="modal-overlay" id="editModal">
        <div class="modal">
            <div class="modal-head">
                <h3>Edit Profile</h3>
                <button class="btn-icon" onclick="closeEditModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" id="editName">
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" id="editPhone">
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select id="editGender">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" id="editDob">
                </div>
            </div>
            <div class="modal-foot">
                <button class="btn-outline" onclick="closeEditModal()">Cancel</button>
                <button class="btn-primary" onclick="saveProfile()"><i class="fa-solid fa-check"></i> Save
                    Profile</button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="logoutModal">
        <div class="modal" style="width: 380px;">
            <div class="modal-head">
                <h3>Confirm Logout</h3>
                <button class="btn-icon" onclick="closeLogoutModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="modal-body" style="text-align: center; padding: 30px 20px;">
                <p style="color: #4b5563; margin: 0;"><strong>Are you sure you want to log out of
                        your<br>account?</strong></p>
            </div>
            <div class="modal-foot" style="justify-content: center;">
                <button class="btn-outline" onclick="closeLogoutModal()">Cancel</button>
                <button class="btn-primary" style="background: #4E0B99; border: none;" onclick="confirmLogout()">
                    <i class="fa-solid fa-right-from-bracket"></i> Log Out
                </button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="successModal">
        <div class="modal" style="width: 350px;">
            <div class="modal-body" style="text-align: center; padding: 40px 20px 20px;">
                <i class="fa-solid fa-circle-check" style="font-size: 4rem; color: #059669; margin-bottom: 15px;"></i>
                <h3 style="color: #1f2937; margin-bottom: 10px; font-size: 18px;">Success!</h3>
                <p style="margin-bottom: 25px; color: #6b7280; font-size: 13px;">Your profile has been updated
                    successfully.</p>

                <button class="btn-primary" onclick="closeSuccessModal()"
                    style="width: 100%; justify-content: center;">Done</button>
            </div>
        </div>
    </div>
    <script>
        let dots = "../";
    </script>
    <script src="../Assets/JavaScript/notifications.js"></script>

</body>

</html>