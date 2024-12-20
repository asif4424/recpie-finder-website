<?php
session_start();

// Redirect to home page if the user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: home.php");
    exit();
}

// Otherwise, redirect to the login page
header("Location: login.php");
exit();
?>
