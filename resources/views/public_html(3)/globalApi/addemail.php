<?php
require_once __DIR__ . "/connection.php";
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
session_start();

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $account_number = $_SESSION["account_number"];
    $new_email = $_POST["new_email"];

    // Check if the email already exists
    $check_query = "SELECT email FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $new_email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // Email already exists, return a response
        $response['status'] = 'fail';
        $response['message'] = 'Email already exists.';
        echo json_encode($response);
        exit;
    }
    // Generate a unique verification code
    $token = bin2hex(random_bytes(50)); // Generate a random 50 characters token
    $code_token = strtoupper(substr($token, 0, 6)); // Convert the code to uppercase

    // Prepare an UPDATE statement
    $query = "UPDATE users SET pending_email = ?, email_verification_code = ? WHERE account_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $new_email, $code_token, $account_number);

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
        // Pending email updated successfully
        $response['status'] = 'success';
        $response['message'] = 'Verification email has been sent to your new email address.';

        // Send verification code via email
        $mail = new PHPMailer(true);
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'cooperative.partners.2324@gmail.com';
        $mail->Password = 'kxsk xjse agze brxx';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->isHTML(true);
        $mail->setFrom('cooperative.partners.2324@gmail.com', 'Cooperative');
        $mail->addAddress($new_email);
        // $link = "<a href='https://cooperative-portal-2324.com/verifyemail.php?token=" . $token . "'>Verify Email</a>";
        $mail->Subject = 'Email Verification Code';
        $mail->Body    = "Enter the code to verify your email. " . $code_token;

        if(!$mail->send()) {
            $response['status'] = 'fail';
            $response['message'] = 'Mailer Error: ' . $mail->ErrorInfo;
            // Redirect to addemail.php if the mail fails to send
            header('Location: /addemail.php');
            exit;
        } else {
           // Assuming mail sending is successful
            $response['status'] = 'success';
            $response['message'] = 'Verification email has been sent to your new email address.';
            $response['redirectUrl'] = '/emailverification.php?email=' . urlencode($new_email);
            echo json_encode($response);
            exit;
        }
    } else {
        $response['status'] = 'fail';
        $response['message'] = 'An error occurred. Please try again later.';
        echo json_encode($response);
    }
}

// Close statement
$stmt->close();
?>