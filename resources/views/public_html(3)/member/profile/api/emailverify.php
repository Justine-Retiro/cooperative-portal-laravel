<?php
session_start();

$response = array();
// Database connection
require_once "connection.php";

if (isset($_POST["verify_email"])) {

    $code = $_POST["code"]; // Get the code from POST data

    // Fetch the user data from the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $stmt = $conn->prepare("UPDATE users SET is_verified = 1 WHERE code = ?");
        $stmt->bind_param("s", $code);
        $stmt->execute();

        if ($stmt->affected_rows == 0) {
            $response['status'] = 'fail';
            $response['message'] = 'Invalid code';        
        }

        $response['status'] = 'success';
        $response['role'] = $user['role']; // Send the role directly without switch case
    } else {
        die("No matching user found.");
    }

} else {
    // Invalid code
    // Redirect the user to an error page
    $response['status'] = 'fail';
    $response['message'] = 'Invalid code';
    $response['redirect'] = '/error.php';
    echo json_encode($response);
}
echo json_encode($response);

?>