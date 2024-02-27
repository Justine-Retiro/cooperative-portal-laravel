$(document).ready(function() {
    var timer;
    var countdown = 60; // 60 seconds for example

    function startResendTimer() {
    var countdown = $('#resend_countdown').val(); // Get the countdown value from the hidden input
    $('#resend_code').text('Resend in ' + countdown + 's').prop('disabled', true).css('pointer-events', 'none'); // Set initial text and disable pointer events

    timer = setInterval(function() {
        countdown--;
        $('#resend_countdown').val(countdown); // Update the countdown value in the hidden input
        if (countdown > 0) {
            $('#resend_code').text('Resend in ' + countdown + 's');
        } else {
            clearInterval(timer);
            $('#resend_code').prop('disabled', false).css('pointer-events', 'auto'); // Enable pointer events
            $('#resend_code').text('Tap here to resend the code'); // Change the text of resend_code
            $('#resend_countdown').val('10'); // Reset the countdown value
        }
    }, 1000);
}

    // Refactored resend code click event
    $('#resend_code').off().click(function() {
        var $button = $(this);
        $button.prop('disabled', true); // Disable the button to prevent double posts

        // Implement AJAX call to resend the code
        $.ajax({
            url: $button.closest('form').attr('action'), 
            type: 'POST',
            data: { account_number: $('input[name="account_number"]').val(), resendcode: true },
            success: function(response) {
                var data = JSON.parse(response);
                if(data.status == 'success'){
                    toastr.success(data.message);
                    startResendTimer(); // Restart the timer after successful resend
                } else {
                    toastr.error(data.message);
                }
            },
            error: function(xhr, status, error) {
                toastr.error('An error occurred: ' + error);
            },
            complete: function() {
                $button.prop('disabled', false); // Re-enable the button
            }
        });
    });
    
    $('.changepassword').off().click(function(event) {
        event.preventDefault();
        var password = $('#password').val();
        var confirmPassword = $('#confirm_password').val();
    
        if (password && confirmPassword && password === confirmPassword) {
            var submitBtn = 'changepassword';
            var form = $(this).closest('form');
            changeProfile(form, submitBtn);
        } else {
            toastr.error('Please fill in and confirm your password before changing it.');
        }
    });
    
   
    $('#show_password').change(function() {
        if($(this).is(":checked")) {
            $('#current_password').attr('type', 'text');
            $('#new_password').attr('type', 'text');
            $('#confirm_password').attr('type', 'text');

        } else {
            $('#current_password').attr('type', 'password');
            $('#new_password').attr('type', 'password');
            $('#confirm_password').attr('type', 'password');
        }
    });

    $('.password-input').on('input', function (){
        var password = $('#new_password').val();
        var confirmPassword = $('#confirm_password').val();
        var passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/;
        
        if (password === confirmPassword) {
            if (passwordRegex.test(password)) {
                $('#alert_password').removeClass('d-none').show().removeClass('text-muted text-danger alert-danger').addClass('text-success alert-success').text('Password is matched!');
            } else {
                $('#alert_password').removeClass('d-none').show().removeClass('text-muted text-success').addClass('text-danger').text('Password must be at least 8 characters long and include at least one special character.');
            }
        } else {
            $('#alert_container').removeClass('d-none').show().removeClass('text-muted text-success').addClass('text-danger').text('Passwords do not match.');
        }
    });
    
    // Show modal only if email inputs are filled
    $('.changeemail').click(function(event) {
        event.preventDefault();
        var email = $('#email').val();
        var confirmEmail = $('#confirm_email').val();
    
        if (email && confirmEmail && email === confirmEmail) {
            var submitBtn = 'changeemail';
            var form = $(this).closest('form');
            changeProfile(form, submitBtn);
            startResendTimer(); // Start the timer when the modal is shown
        } else {
            $('#alert_email').removeClass('d-none alert-success').addClass('alert-danger').text('Please fill in and confirm your email before verification.');
        }
    });

    $('.email-input').on('input', function (){
        var email = $('#email').val();
        var confirmEmail = $('#confirm_email').val();

        if (email && confirmEmail && email === confirmEmail) {
            $('#alert_email').removeClass('d-none alert-danger').addClass('alert-success').text('Emails match!');
        } else if (email || confirmEmail) {
            $('#alert_email').removeClass('d-none alert-success').addClass('alert-danger').text('Emails do not match.');
        } else {
            $('#alert_email').addClass('d-none').text('');
        }
    });
    
    // Handle the form submission with AJAX
    $('#code_verify').submit(function(event) {
        event.preventDefault();
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize() + '&verifyemail=true',
            success: function(response) {
                var data = JSON.parse(response);
                if(data.status == 'success'){
                    location.reload();
                    toastr.success(data.message);
                    $('#confirmModal').modal('hide'); // Hide the modal after successful verification
                    clearInterval(timer); // Clear the timer
                    $('#resend_countdown').val('200'); // Reset the countdown value
                    $('#resend_code').prop('disabled', false); // Enable the "Resend Code" button
                    $('#resend_timer').text(''); // Clear the timer text
                } else {
                    toastr.error(data.message);
                }
            },
            error: function(xhr, status, error) {
                toastr.error('An error occurred: ' + error);
            }
        });
    });
    
    
    // Refactored changeProfile function to include nonceSession
    function changeProfile(form, submitBtn, $button) {
        $button.prop('disabled', true); // Disable the button to prevent double posts

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize() + '&' + submitBtn + '=true',
            success: function(response) {
                var data = JSON.parse(response);
                if(data.status == 'success'){
                    toastr.success(data.message);
                    if(submitBtn === 'changeemail'){
                         startResendTimer();
                        $('#confirmModal').modal('show');
                    }
                } else {
                    if (data.message.includes('email is already in use')) {
                        $('#alert_email').removeClass('d-none alert-success').addClass('alert-danger').text(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }
            },
            error: function(xhr, status, error) {
                toastr.error('An error occurred: ' + error);
            },
            complete: function() {
                $button.prop('disabled', false); // Re-enable the button
            }
        });
    }
    
    
    // Refactored click event for changeprofile, changepassword, changeemail
    $('.changeprofile, .changepassword, .changeemail').off().click(function(event) {
        event.preventDefault();
        var $button = $(this);
        var submitBtn = $button.hasClass('changeprofile') ? 'changeprofile' : ($button.hasClass('changepassword') ? 'changepassword' : 'changeemail');
        var form = $button.closest('form');
        changeProfile(form, submitBtn, $button);
    });
    
});
