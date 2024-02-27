<?php
session_start();

require_once __DIR__ . "/connection.php";

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $user = $result->fetch_assoc();
        if($user["is_verified"] == "0"){
            $stmt = $conn->prepare("UPDATE users SET is_verified = '1' WHERE token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                // Redirect the user to their respective dashboard based on their role
                switch ($user['role']) {
                    case 'admin':
                        header('Location: /admin/dashboard/dashboard.php');
                        break;
                    case 'master':
                        header('Location: /master/dashboard/dashboard.php');
                        break;
                    case 'mem':
                        header('Location: /member/dashboard/dashboard.php');
                        break;
                    default:
                        // Invalid role
                        // Redirect the user to an error page
                        header('Location: /index.php');
                }
            }
            exit();
        } else {
            $_SESSION['status'] = "Email already in use, please use different email.";
            header("location: /index.php");
            exit();
        }
    }
    

} else {
    $_SESSION['status'] = "Unauthorized section";

    header("location: /index.php");
}

// if ($user && is_array($user)) {
//     // Token is valid
//     // You can now allow the user to change their email
//     // Update the email column with the pending email
//     $stmt = $conn->prepare("UPDATE users SET email = pending_email, pending_email = NULL WHERE token = ?");
//     $stmt->execute([$token]);
//     // Redirect the user to their respective dashboard based on their role
//     switch ($user['role']) {
//         case 'admin':
//             header('Location: /admin/dashboard/dashboard.php');
//             break;
//         case 'master':
//             header('Location: /master/dashboard/dashboard.php');
//             break;
//         case 'mem':
//             header('Location: /member/dashboard/dashboard.php');
//             break;
//         default:
//             // Invalid role
//             // Redirect the user to an error page
//             header('Location: /index.php');
//     }
//     exit();
// } else {
//     // Invalid token
//     // Redirect the user to an error page
//     header('Location: /error.php');
//     exit();
// }
