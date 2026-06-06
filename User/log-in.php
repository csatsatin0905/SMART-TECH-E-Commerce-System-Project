<?php $successfulreg = $_SESSION["successful_registration"] ?? "";
$logerror = $_SESSION["login"]["error"] ?? "";
unset($_SESSION["successful_registration"]);
unset($_SESSION["login"]["error"]); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Smart Tech</title>
  <link rel="stylesheet" href="../Assets/CSS/log-sign--up.css">
</head>

<body>

  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>

  <div class="container">

    <div class="left-panel">
      <div class="gloss-overlay"></div>
      <img src="../Assets/pictures/LoginSignup Picture.png" alt="Log-In design" class="rotate">
    </div>

    <div class="right-panel">
      <div class="login-box">

        <h1 class="store-name">Smart Tech</h1>
        <?php if (isset($successfulreg)): ?>
          <p style="color: green; font-size: 0.9em; margin-top: 10px;">
            Registration successful! Please log in to your account.
          </p>
        <?php endif; ?>
        <h2>User Login</h2>
        <p>Welcome back! Please login to your account.</p>

        <form action="../Actions/Log_In/login-process.php" method="POST">

          <label for="username">User Name</label>
          <input type="email" id="username" placeholder="Username" required>

          <label for="password">Password</label>
          <input type="password" id="password" placeholder="••••••••" required>

          <div class="options">
            <label>
              <input type="checkbox"> Remember Me
            </label>
            <a href="#">Forgot Password?</a>
          </div>

          <?php if (isset($logerror)): ?>
            <p style="color: red; font-size: 0.9em; margin-top: 10px;">
              <?php echo $logerror ?>
            </p>
          <?php endif; ?>

          <button type="submit">Log-In</button>

          <div class="signup">
            <span>New User?</span>
            <a href="sign-up.php">Signup</a>
          </div>

        </form>

      </div>
    </div>

  </div>

</body>

</html>