<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

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

    // Check if email exists and is verified
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_verified = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // User exists and is verified, send email
        $mail = new PHPMailer(true);
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        try {
            //Server settings
            $mail->SMTPDebug = 2;                                 
            $mail->isSMTP();                                      
            $mail->Host = 'smtp.gmail.com';  
            $mail->SMTPAuth = true;                               
            $mail->Username = 'cooperative.partners.2324@gmail.com';                 
            $mail->Password = 'kxsk xjse agze brxx';                           
            $mail->SMTPSecure = 'tls';                            
            $mail->Port = 587;                                    

            //Recipients
            $mail->setFrom('cooperative.partners.2324@gmail.com', 'Cooperative');
            $mail->addAddress($email);     

            //Content
            $mail->isHTML(true);                                  
            $mail->Subject = 'Password reset request';
            $token = bin2hex(random_bytes(50)); // Generate a random 50 characters token
            $code_token = strtoupper(substr($token, 0, 6));
            // $resetLink = "Click on this link to reset your password: <a href='https://cooperative-portal-2324.com/resetpassword.php?token=".$token."'>Reset Password</a>";
            // $resetCode = "Enter this code to reset your password: " . $code_token;
            $mail->Body    = "Enter the code to reset your password. \n" . $code_token;
            // Store the token in the database for later verification
            $stmt = $pdo->prepare("UPDATE users SET code = ?, reset_token = ?  WHERE email = ?");
            $stmt->execute([$code_token, $token, $email ]);

            if ($mail->send()) {
                $_SESSION['toastr'] = "Your password reset link has been sent to your email.";
                header('Location: /emailpasswordverification.php?email='. urlencode($email));
                exit;
            }
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }
    } else {
        // User does not exist or is not verified
        echo 'No account found for this email address or the account is not verified.';
    }
}