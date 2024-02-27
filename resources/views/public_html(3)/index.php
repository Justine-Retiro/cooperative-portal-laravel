<?php 
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




    <!-- Styles -->
    <link rel="stylesheet" href="/globalStatic/style.css">

</head>
<body>
    <div class="container-fluid d-flex justify-content-center align-items-center vh-100">
        <form action="/globalApi/login.php" method="post">
            <div class="row px-4">
                <div class="col-lg-12 p-md-5 w-100">
                    <h2 class="me-md-5">Cooperative Portal Login</h2>
                    <div class="row justify-content-center" >
                        <div class="col-lg-12 my-2">

                            <label for="account_number">Account Number</label>
                            <input type="text" class="form-control mb-2" placeholder="Account Number" name="account_number" aria-label="Username" aria-describedby="addon-wrapping">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                            <div class="mt-2">
                                <input type="checkbox" id="show_password">
                                <label for="show_password">Show Password</label>
                            </div>
                            <a href="/forgotpassword.php" class="text-decoration-none" ><p class="pt-2 mb-0" >Forgot password</p></a>
                            <button class="btn btn-primary mt-3 w-100">Login</button>
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

<!-- Alert validation -->
<script src="/globalStatic/validation.js" type="text/javascript"></script>

</body>
</html>