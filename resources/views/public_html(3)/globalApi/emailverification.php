<?php

session_start();

$response = array();
// Database connection
require_once "connection.php";
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendVerificationEmail($conn, $account_number, $new_email) {

    $token = bin2hex(random_bytes(50));
    $code_token = strtoupper(substr($token, 0, 6));

    // Update the users table with the pending email and verification code
    $query = "UPDATE users SET pending_email = ?, email_verification_code = ? WHERE account_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $new_email, $code_token, $account_number);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $mail = new PHPMailer(true);
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'cooperative.partners.2324@gmail.com';
        $mail->Password = 'kxsk xjse agze brxx';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('cooperative.partners.2324@gmail.com', 'Cooperative');
        $mail->addAddress($new_email);
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $mail->Body = "Email confirmation code:\n \n" . $code_token;

        if (!$mail->send()) {
            return ['status' => 'fail', 'message' => 'Mailer Error: ' . $mail->ErrorInfo];
        } else {
            return ['status' => 'success', 'message' => 'Verification email has been sent to your new email address.'];
        }
    } else {
        return ['status' => 'fail', 'message' => 'An error occurred. Please try again later.'];
    }
}


function verifyEmail($conn, $account_number, $verification_code) {
    // Check the verification code and get the pending email
    $query = "SELECT pending_email FROM users WHERE account_number = ? AND email_verification_code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $account_number, $verification_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Update the email in the users table
        $updateQuery = "UPDATE users SET email = ?, is_verified = true, pending_email = NULL, email_verification_code = NULL WHERE account_number = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("ss", $user['pending_email'], $account_number);
        if ($updateStmt->execute()) {
            return ['status' => 'success', 'message' => 'Email successfully updated.'];
        } else {
            return ['status' => 'fail', 'message' => 'Database error: ' . $updateStmt->error];
        }
    } else {
        return ['status' => 'fail', 'message' => 'Invalid verification code.'];
    }
}

function resendVerificationEmail($conn, $account_number) {
    // Get the pending email from the users table
    $query = "SELECT pending_email FROM users WHERE account_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $account_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Resend the verification email
        return sendVerificationEmail($conn, $account_number, $user['pending_email']);
    } else {
        return ['status' => 'fail', 'message' => 'No pending email found.'];
    }
}


if (isset($_POST["verify_email"])) {
    $code = $_POST["code"];
    $account_number = $_POST["account_number"];
    $response = verifyEmail($conn, $account_number, $code);
} elseif (isset($_POST["resendcode"])) {
    $account_number = $_POST["account_number"];
    $response = resendVerificationEmail($conn, $account_number);
}

echo json_encode($response);

?>