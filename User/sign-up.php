<?php
session_start();
$firstName = $_SESSION["registration"]["firstName"] ?? "";
$lastName = $_SESSION["registration"]["lastName"] ?? "";
$email = $_SESSION["registration"]["email"] ?? "";
$regerror = $_SESSION["registration"]["error"] ?? "";
unset($_SESSION["reg"], $_SESSION["registration"]["firstName"], $_SESSION["registration"]["lastName"], $_SESSION["registration"]["email"], $_SESSION["registration"]["error"], $_SESSION["login"]["error"], $_SESSION["successful_registration"]); //so that these variables won't persist when refreshed
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../Assets/CSS/log-sign--up.css">
  <title>Sign Up - Smart Tech</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

  <div class="glow-orb orb-1"></div>
  <div class="glow-orb orb-2"></div>

  <div class="container">

    <div class="left-panel">
      <div class="gloss-overlay"></div>
      <img src="../Assets/pictures/LoginSignup Picture.png" alt="Sign-Up design" class="rotate">
    </div>

    <div class="right-panel">
      <div class="login-box">

        <h1 class="store-name">Smart Tech</h1>

        <h2>Sign-Up</h2>
        <p>Welcome! Please register your account.</p>

        <form action="../Actions/Sign_Up/registration-process.php" method="POST">

          <label for="first-name">First Name</label>
          <input type="text" id="first-name" name="first-name" placeholder="Enter your first name" required
            value="<?= htmlspecialchars($firstName) ?>">

          <label for="last-name">Last Name</label>
          <input type="text" id="last-name" name="last-name" placeholder="Enter your last name" required
            value="<?= htmlspecialchars($lastName) ?>">

          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="username@Email.com" required
            value="<?= htmlspecialchars($email) ?>">

          <span>Password</span>
          <label for="password" style="position: relative;">
            <input type="password" id="password" name="password" placeholder="••••••••" required>
            <span class="toggle-password" id="toggle-password"
              style="position: absolute; right: 10px; top: 40%; transform: translateY(-50%); cursor: pointer;">
              <i class="fa-solid fa-eye"></i>
            </span>
          </label>

          <span>Confirm Password</span>
          <label for="confirm-password" style="position: relative;">
            <input type="password" id="confirm-password" name="cpassword" placeholder="••••••••" required>
            <span class="toggle-password" id="toggle-confirm-password"
              style="position: absolute; right: 10px; top: 40%; transform: translateY(-50%); cursor: pointer;">
              <i class="fa-solid fa-eye"></i>
            </span>
          </label>


          <?php if (isset($regerror)): ?>
            <p style="color: red; font-size: 0.9em; margin-top: 10px;">
              <?php echo $regerror ?>
            </p>
          <?php endif; ?>



          <button type="submit">Sign-Up</button>

          <div class="signup">
            <span>Already have account?</span>
            <a href="log-in.php">Log-In</a>
          </div>
        </form>

      </div>
    </div>

  </div>

  <script>
    const toggleConfirmPassword = document.querySelector("#toggle-confirm-password");
    const confirmPasswordInput = document.querySelector("#confirm-password");
    toggleConfirmPassword.addEventListener("click", function () {
      const type = confirmPasswordInput.getAttribute("type") === "password" ? "text" : "password";
      confirmPasswordInput.setAttribute("type", type);
      this.classList.toggle("fa-eye-slash");
    });

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