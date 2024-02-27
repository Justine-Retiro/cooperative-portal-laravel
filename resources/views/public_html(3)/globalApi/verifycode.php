<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$response = array();
// Database connection
require_once "connection.php";
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $code = $_POST['code'];

//     // Database connection
//     $host = 'localhost';
//     $db   = 'u148532513_cooperative';
//     $user = 'u148532513_coopportal';
//     $pass = '0kO&KWIHD2O@';
//     $charset = 'utf8mb4';

//     $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
//     $opt = [
//         PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
//         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
//         PDO::ATTR_EMULATE_PREPARES   => false,
//     ];
//     $pdo = new PDO($dsn, $user, $pass, $opt);

//     // Check if the code exists in the database
//     $stmt = $pdo->prepare("SELECT * FROM users WHERE code = ?");
//     $stmt->execute([$code]);
//     $user = $stmt->fetch();

//     if ($user) {
//         // If the user exists and the code matches, redirect to the password reset page
//         header('Location: /resetpassword.php?code=' . $code);
//         exit;
//     } else {
//         // If the code does not match, show an error message
//         echo 'The code you entered is incorrect.';
//     }
// }

function sendVerificationEmail($conn, $email) {

    $token = bin2hex(random_bytes(50));
    $code_token = strtoupper(substr($token, 0, 6));

    // Update the users table with the pending email and verification code
    $query = "UPDATE users SET code = ?  WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $code_token, $email);
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
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Password reset request';
        $mail->Body = "Reset password code:\n \n" . $code_token;

        if (!$mail->send()) {
            return ['status' => 'fail', 'message' => 'Mailer Error: ' . $mail->ErrorInfo];
        } else {
            return ['status' => 'success', 'message' => 'Code for reset password has been sent to your email address.'];
        }
    } else {
        return ['status' => 'fail', 'message' => 'An error occurred. Please try again later.'];
    }
}


function verifyCode($conn, $email, $code) { 
    
    $query = "SELECT email FROM users WHERE email = ? AND code = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $code); 
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $status['success'] = 'Success'; 
        $response['redirectUrl'] = '/resetpassword.php?code=' . $code;
        return $response;
    } else {
        return ['status' => 'fail', 'message' => 'Invalid verification code.'];
    }
}

function resendVerificationEmail($conn, $email) {
    // Get the pending email from the users table
    $query = "SELECT email FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email); // Bind $email instead of $account_number
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Resend the verification email
        return sendVerificationEmail($conn, $user['email']);
    } else {
        return ['status' => 'fail', 'message' => 'No pending email found.'];
    }
}


if (isset($_POST["verify_code"])) {
    $code = $_POST["code"];
    $email = $_POST["email"];
    $response = verifyCode($conn, $email, $code); 
} elseif (isset($_POST["resendcode"])) {
    $email = $_POST["email"];
    $response = resendVerificationEmail($conn, $email);
}

echo json_encode($response);

?>

