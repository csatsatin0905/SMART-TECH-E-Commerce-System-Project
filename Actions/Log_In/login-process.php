<?php
session_start();

require_once("../../Database/db.php");

// Get form data
$email = isset($_POST["email"])
    ? trim(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
    : "";
$password = isset($_POST["password"])
    ? trim(filter_var($_POST["password"], FILTER_SANITIZE_SPECIAL_CHARS))
    : "";

if ($email && $password) {
    $sql = "SELECT user_id, first_name, last_name, password FROM users WHERE email = :email AND is_active = 1";
    // Prepare and execute query
    $stmt = $pdo->prepare($sql); //Fetch the password of the user
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) { //Compare the hashed password of the user to the entered password
        $_SESSION['user_id'] = $user['user_id'];
        $name = $user["first_name"] . " " . $user["last_name"];
        $_SESSION['name'] = $name;
        header("Location: ../../home.php");
        exit;
    }

    $_SESSION["login"]["error"] = "Invalid username or password.";
    $_SESSION["login"]["email"] = $email;
    header("Location: ../../User/log-in.php");
    exit;
} else {
    $_SESSION["login"]["error"] = "Email and password must be both filled";
    $_SESSION["login"]["email"] = $email;
    header("Location: ../../User/log-in.php");
    exit;
}

