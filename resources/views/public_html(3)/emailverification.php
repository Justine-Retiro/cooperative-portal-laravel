<?php
session_start();
include("globalApi/is_verified_session.php");


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Email Verification</title>
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
    <form id="verificationForm" action="/globalApi/emailverification.php" method="post">
        <div class="row px-4">
            <div class="col-lg-12 p-md-5 w-100">
                <h2 class="me-md-5">Email Verification</h2>
                    <p class="mb-0">  
                        <small> Did not receive the code? Check your spam folder. </small>
                    </p>

                <div class="row justify-content-center">
                    <div class="col-lg-12 my-2">
                        
                        <label class="mb=2" for="code">Enter the verification code</label>
                        <input type="hidden" class="form-control" name="account_number" value="<?php echo $_SESSION["account_number"] ?>" id="account_number">
                        <input type="text" class="form-control class="mb=2"" placeholder="Verification code" name="code" id="code">
                        <input type="hidden" id="resend_countdown" value="60">
                        <div class="col-lg-12">
                        <p class="mb-0"> Didn't get the code?
                        <span id="resend_code" class="text-primary" disabled>Resend <span id="resend_timer"></span></span>
                        </p>
                    </div>
                        <button class="btn btn-primary mt-3 w-100" type="submit">Verify</button>
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
<script>
$(document).ready(function () {
           var timer;
            var countdown = 60; 
        
            function startResendTimer() {
            var countdown = $('#resend_countdown').val(); 
            $('#resend_code').text('Resend in ' + countdown + 's').prop('disabled', true).css('pointer-events', 'none'); 
        
            timer = setInterval(function() {
                countdown--;
                $('#resend_countdown').val(countdown); 
                if (countdown > 0) {
                    $('#resend_code').text('Resend in ' + countdown + 's');
                } else {
                    clearInterval(timer);
                    $('#resend_code').prop('disabled', false).css('pointer-events', 'auto'); 
                    $('#resend_code').text('Tap here to resend the code'); 
                    $('#resend_countdown').val('60'); 
                }
            }, 1000);
        }
        
        $('#resend_code').off().click(function() {
            var $button = $(this);
            $button.prop('disabled', true); 

            $.ajax({
                url: '/globalApi/emailverification.php', 
                type: 'POST',
                data: { account_number: $('input[name="account_number"]').val(), resendcode: true },
                success: function(response) {
                    var data = JSON.parse(response);
                    if(data.status == 'success'){
                        toastr.success(data.message);
                        startResendTimer(); 
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('An error occurred: ' + error);
                },
                complete: function() {
                    $button.prop('disabled', false); 
                }
            });

        });


        $('#verificationForm').on('submit', function (event) {
            event.preventDefault();
            var code = $('#code').val(); 
            var email = $('input[name="email"]').val();
            var account_number = $('input[name="account_number"]').val();
            var verify_email = true;
            $.ajax({
                url: '/globalApi/emailverification.php',
                type: 'POST',
                data: { code: code, verify_email: verify_email, account_number: account_number },
                success: function (response) {
                    try {
                        var data = JSON.parse(response);
                        if (data.status === 'success') {
                            switch (data.role) {
                                case 'admin':
                                    window.location.href = '/admin/dashboard/dashboard.php?toastr=Successfully logged in!';
                                    break;
                                case 'master':
                                    window.location.href = '/master/dashboard/dashboard.php?toastr=Successfully logged in!';
                                    break;
                                case 'mem':
                                    window.location.href = '/member/dashboard/dashboard.php?toastr=Successfully logged in!';
                                    break;
                                default:
                                    window.location.href = '/';
                            }
                        } else {
                            // Show error message
                            alert(data.message);
                        }
                    } catch (e) {
                        toastr.error("Parsing error: " + e + ", Response: " + response);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error("AJAX error: " + textStatus + ", " + errorThrown);
                }
            });
        });
    });
</script>
</body>
</html>
