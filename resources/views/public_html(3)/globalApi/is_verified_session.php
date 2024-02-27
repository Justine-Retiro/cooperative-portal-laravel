<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION["account_number"])) {
    header("Location: /index.php");
    exit();
}

// Check if the email is already verified
if (isset($_SESSION["is_verified"]) && $_SESSION["is_verified"] == 1) {
    header("Location: /index.php"); // Replace with the page you want to redirect to
    exit();
}
?>