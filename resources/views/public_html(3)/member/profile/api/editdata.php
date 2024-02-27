<?php
session_start();
require_once __DIR__ . "/connection.php";
require($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendVerificationEmail($conn, $account_number, $new_email) {

    $checkEmailQuery = "SELECT email FROM users WHERE email = ?";
    $checkEmailStmt = $conn->prepare($checkEmailQuery);
    $checkEmailStmt->bind_param("s", $new_email);
    $checkEmailStmt->execute();
    $checkEmailResult = $checkEmailStmt->get_result();
    if ($checkEmailResult->num_rows > 0) {
        // Email already exists
        return ['status' => 'fail', 'message' => 'This email is already in use.'];
    }

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
        $mail->Body = "Change email confirmation code:\n \n" . $code_token;

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
        $updateQuery = "UPDATE users SET email = ?, pending_email = NULL, email_verification_code = NULL WHERE account_number = ?";
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

function changeProfile($conn) {
    $account_number = $_POST["account_number"];
    $fields = [
        "last_name", "first_name", "middle_name", 
        "civil_status", "mailing_address", 
        "birth_date", "position", "date_employed"
    ];

    $query = "UPDATE clients SET";
    $params = [];

    foreach ($fields as $field) {
        if (!empty($_POST[$field])) {
            $query .= " $field = ?,";
            $params[] = $_POST[$field];
        }
    }

    $query = rtrim($query, ",") . " WHERE account_number = ?";
    $params[] = $account_number;

    $stmt = $conn->prepare($query);
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        return ['status' => 'success', 'message' => 'Profile successfully changed.'];
    } else {
        error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        return ['status' => 'fail', 'message' => 'Database error: ' . $stmt->error];    }
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

function changeEmail($conn) {
    $account_number = $_POST["account_number"];
    $new_email = $_POST["email"];
    return sendVerificationEmail($conn, $account_number, $new_email);
}

function changePassword($conn) {
    $account_number = $_POST["account_number"];
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];

    $query = "SELECT users.*, clients.client_id FROM users 
              INNER JOIN clients ON users.user_id = clients.user_id 
              WHERE clients.account_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $account_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $hashed_password = $user['passwords']; // Ensure 'passwords' is the correct column name for the hashed password

        // Use $current_password instead of $password
        if (password_verify($current_password, $hashed_password)) {
            $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
            $query = "UPDATE users 
                      INNER JOIN clients ON users.user_id = clients.user_id 
                      SET users.passwords = ? 
                      WHERE clients.account_number = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $new_hashed_password, $account_number);
            $stmt->execute();
        
            if ($stmt->affected_rows > 0) {
                return ['status' => 'success', 'message' => 'Password successfully changed.'];
            } else {
                error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
                return ['status' => 'fail', 'message' => 'Database error: ' . $stmt->error];
            }          
        } else {
            return ['status' => 'fail', 'message' => 'Current password is incorrect.'];
        }
    } else {
        return ['status' => 'fail', 'message' => 'Account not found.'];
    }
}

$response = ['status' => 'fail', 'message' => 'No action performed.'];

if (isset($_POST["changeprofile"])) {
    $response = changeProfile($conn);
} elseif (isset($_POST["changeemail"])) {
    $response = changeEmail($conn);
} elseif (isset($_POST["verifyemail"])) {
    $account_number = $_POST["account_number"];
    $verification_code = $_POST["verification_code"];
    $response = verifyEmail($conn, $account_number, $verification_code);
} elseif (isset($_POST["changepassword"])) {
    $response = changePassword($conn);
} elseif (isset($_POST["resendcode"])) {
    $account_number = $_POST["account_number"];
    $response = resendVerificationEmail($conn, $account_number);
}

echo json_encode($response);
?>
