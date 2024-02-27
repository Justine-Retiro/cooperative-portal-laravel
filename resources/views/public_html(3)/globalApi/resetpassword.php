<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['new_password'];
    $token = isset($_POST['token']) ? $_POST['token'] : null;
    $code = isset($_POST['code']) ? $_POST['code'] : null;

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Database connection
    $host = 'localhost';
    $db   = 'u148532513_cooperative';
    $user = 'u148532513_coopportal';
    $pass = '0kO&KWIHD2O@';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $user, $pass, $opt);

    // Extract token from URL
    if ($token) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ?");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

    } elseif ($code) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE code = ?");
        $stmt->execute([$code]);
        $user = $stmt->fetch();

    } else {
        echo 'No token or code provided.';
        exit;
    }

    // Update the user's password in the database
    $stmt = $pdo->prepare("UPDATE users SET passwords = ? WHERE user_id = ?");
    $updateSuccessful = $stmt->execute([$hashedPassword, $user['user_id']]);

    if ($updateSuccessful) {
        // If the password was updated successfully, redirect to the login page
        header('Location: /index.php');
        exit;
    } else {
        // If the password could not be updated, show an error message
        echo 'There was an error updating your password.';
    }
}