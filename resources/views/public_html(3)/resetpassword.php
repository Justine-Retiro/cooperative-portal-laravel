<?php 
session_start();

if (isset($_SESSION['toastr'])) {
    echo '<script>sessionStorage.setItem("toastr", "'.$_SESSION['toastr'].'");</script>';
    unset($_SESSION['toastr']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <!-- CDN'S -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/172203/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body>
    <div class="container-fluid d-flex justify-content-center align-items-center vh-100">
        <form action="/globalApi/resetpassword.php" method="post">
            <div class="row px-4">
                <div class="col-lg-12 p-md-5 w-100">
                    <h2 class="me-md-5">Change Password</h2>
                    <div class="row justify-content-center" >
                        <div class="col-lg-12 my-2">
                            <input type="hidden" name="token" value="<?php echo isset($_GET['token']) ? $_GET['token'] : ''; ?>">
                            <input type="hidden" name="code" value="<?php echo isset($_GET['code']) ? $_GET['code'] : ''; ?>">
                            <div class="alert alert-danger d-none" style="max-width: 350px;" role="alert" id="alert_container">
                                <!-- Container for error -->
                            </div>

                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control" placeholder="New Password" name="new_password" id="new_password">

                            <label for="confirm_new_password">Confirm New Password</label>
                            <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_new_password" id="confirm_new_password">
                            <div class="mt-2">
                                <input type="checkbox" id="show_password">
                                <label for="show_password">Show Password</label>
                            </div>
                            

                            <button class="btn btn-primary mt-3 w-100">Change Password</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

<!-- CDN's -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

<script>
$(document).ready(function() {
    $('#show_password').change(function() {
        if($(this).is(":checked")) {
            $('#new_password').attr('type', 'text');
            $('#confirm_new_password').attr('type', 'text');
        } else {
            $('#new_password').attr('type', 'password');
            $('#confirm_new_password').attr('type', 'password');
        }
    });

    $('form').on('submit', function(event) {
        var newPassword = $('#new_password').val();
        var confirmNewPassword = $('#confirm_new_password').val();

        // Regular expression for password validation
        var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/;
                
        if (!passwordRegex.test(newPassword)) {
            event.preventDefault();
            $('#alert_container').removeClass('d-none').text('Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.');
        } else if (newPassword !== confirmNewPassword) {
            event.preventDefault();
            $('#alert_container').removeClass('d-none').text('Passwords do not match.');
        }
    });
});
</script>

</body>
</html>