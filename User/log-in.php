<?php SESSION_START();
$successfulreg = isset($_SESSION["successful_registration"]) ? $_SESSION["successful_registration"] : "";
$logerror = isset($_SESSION["login"]["error"]) ? $_SESSION["login"]["error"] : "";
$logemail = isset($_SESSION["login"]["email"]) ? $_SESSION["login"]["email"] : "";
unset($_SESSION["successful_registration"]);
unset($_SESSION["login"]["error"]);
unset($_SESSION["login"]["email"]); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Smart Tech</title>
  <link rel="stylesheet" href="../Assets/CSS/log-sign--up.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        <?php if (!empty($successfulreg)): ?>
          <p style="color: green; font-size: 0.9em; margin-top: 10px;">
            Registration successful! Please log in to your account.
          </p>
        <?php endif; ?>
        <h2>User Login</h2>
        <p>Welcome back! Please login to your account.</p>

        <form action="../Actions/Log_In/login-process.php" method="POST">

          <label for="username">Email</label>
          <input type="email" id="username" name="email" placeholder="Enter Email" required
            value="<?php echo htmlspecialchars($logemail); ?>">

          <span>Password</span>
          <label for="password" style="position: relative;">
            <input type="password" id="password" name="password" placeholder="••••••••" required>
            <span class="toggle-password" id="toggle-password"
              style="position: absolute; right: 10px; top: 40%; transform: translateY(-50%); cursor: pointer;">
              <i class="fa-solid fa-eye"></i>
            </span>
          </label>



          <?php if (!empty($logerror)): ?>
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

  <script>


    const togglePassword = document.querySelector("#toggle-password");
    const passwordInput = document.querySelector("#password");
    togglePassword.addEventListener("click", function () {
      const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
      passwordInput.setAttribute("type", type);
      this.classList.toggle("fa-eye-slash");
    });

  </script>

</body>

</html>