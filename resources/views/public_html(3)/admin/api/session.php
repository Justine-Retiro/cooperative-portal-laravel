<?php
session_start(); // Start the session at the beginning

// Assuming $conn is your database connection
require_once __DIR__ . '/connection.php';

// Set session timeout to 1 hour
$inactive = 3600; // 3600 seconds = 1 hour

// Check if timeout condition is met
if (isset($_SESSION['timeout']) && (time() - $_SESSION['timeout']) > $inactive) {
    // Last request was more than 1 hour ago
    session_unset(); // unset $_SESSION variable for this page
    session_destroy(); // destroy session data
}

$_SESSION['timeout'] = time(); // Update session timeout time

// Check if the user is logged in and has an admin role, if not then redirect to login page
if(!isset($_SESSION["user_id"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin'){
    header("Location: /");
    exit;
}

$sql = "SELECT clients.first_name, clients.last_name FROM clients INNER JOIN users ON clients.user_id = users.user_id WHERE users.user_id = " . $_SESSION['user_id'] . " AND users.role = 'admin'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $_SESSION['first_name'] = $row['first_name'];
        $_SESSION['last_name'] = $row['last_name'];
    }
} else {
    // If no results, redirect to login.php
    header("Location: /");
    exit;
}
?>