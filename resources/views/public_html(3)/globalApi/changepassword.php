<?php
require_once __DIR__ . "/connection.php";
session_start();

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_number = $_SESSION["account_number"];
    $new_password = $_POST["new_password"];

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Prepare an UPDATE statement
    $query = "UPDATE users 
              INNER JOIN clients ON users.user_id = clients.user_id 
              SET users.passwords = ? 
              WHERE clients.account_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $hashed_password, $account_number);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        // Password updated successfully
        $response['status'] = 'success';
        $response['message'] = 'Password changed successfully.';
    } else {
        $response['status'] = 'fail';
        $response['message'] = 'An error occurred. Please try again later.';
    }

    // Close statement
    $stmt->close();
}

echo json_encode($response);
?>