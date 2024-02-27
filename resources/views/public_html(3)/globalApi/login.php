<?php
require_once __DIR__ . "/connection.php";
session_start();

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_number = $_POST["account_number"];
    $password = $_POST["password"];

    $query = "SELECT users.*, clients.client_id FROM users 
              INNER JOIN clients ON users.user_id = clients.user_id 
              WHERE clients.account_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $account_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $hashed_password = $user['passwords']; // Check the actual column name

        if (password_verify($password, $hashed_password)) {
            // Password is correct, start the session
            $_SESSION["loggedin"] = true;
            $_SESSION["account_number"] = $account_number;
            $_SESSION["role"] = $user['role']; // Store the user's role in the session
            $_SESSION["user_id"] = $user['user_id']; // Store the user's id in the session
            $_SESSION["client_id"] = $user['client_id']; // Store the user's client_id in the session
        
            $response['status'] = 'verify_birthdate';
            $response['role'] = $user['role'];
        
            // Check if the password is the default one
            if (password_verify('123', $hashed_password)) {
                $response['status'] = 'change_password';
            }
        } else {
            $response['status'] = 'fail';
            $response['message'] = 'Invalid password.';
        }
    } else {
        $response['status'] = 'fail';
        $response['message'] = 'Invalid account number.';
    }
}

echo json_encode($response);
?>