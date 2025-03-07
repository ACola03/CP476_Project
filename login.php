<?php
session_start();

# in case user enters login.php directly without going through index.php
if (!isset($_POST['username']) || !isset($_POST['password'])) {
    header("Location: index.php");
    exit();
}

# save form variables
$username = $_POST['username'];
$password = $_POST['password'];

# save session variables
$_SESSION['username'] = $username;
$_SESSION['password'] = $password;

# Failed login attempts for security
$maxAttempts = 3;
if (!isset($_SESSION["loginAttempts"])){
    $_SESSION["loginAttempts"] = $maxAttempts;
}

// Dummy credentials for validation
$valid_username = "admin";
$valid_password = "password123";

if ($username === $valid_username && $password === $valid_password) {
    # reset attempts
    $_SESSION["loginAttempts"] = $maxAttempts;

    # redirect to sql page
    header(header: "Location: AccessGranted.php");
    exit();
} else {

    # redirect to error page
    $_SESSION["loginAttempts"] -= 1;

    if ($_SESSION["loginAttempts"] <= 0){
        header("Location: LockedOut.php");
        exit();
    }

    header("Location: AccessDenied.php");
    exit();
}
?>
