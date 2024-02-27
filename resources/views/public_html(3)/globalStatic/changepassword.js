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
    
    var passwordRegex = /^(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;

    // Check if the password matches the input
    $('.password_holder').on('input', function() {
        $('#password_holder').show();
        var password = $('#new_password').val();
        var confirmPassword = $('#confirm_password').val();
        if (password === confirmPassword) {
            if (passwordRegex.test(password)) {
                // If the password is valid, show a success message
                $('#alert').show().removeClass('text-muted text-danger').addClass('text-success').text('Password is matched!');
            } else {
                // If the password is invalid, show an error message
                $('#alert').show().removeClass('text-muted text-success').addClass('text-danger').text('Password must be at least 8 characters long and include at least one special character.');
            }
        } else {
            // If the passwords do not match, show an error message
            $('#alert').show().removeClass('text-muted text-success').addClass('text-danger').text('Passwords do not match.');
        }
    });


    $('form').on('submit', function(event) {
        event.preventDefault();

        var newPassword = $('#new_password').val();
        var confirmNewPassword = $('#confirm_new_password').val();

        // Regular expression for password validation
        var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}$/;

        if (!passwordRegex.test(newPassword)) {
            $('#alert_container').removeClass('d-none').text('Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.');
            return;
        } else if (newPassword !== confirmNewPassword) {
            $('#alert_container').removeClass('d-none').text('Passwords do not match.');
            return;
        }

        $.ajax({
            url: '/globalApi/changepassword.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    setTimeout(function() {
                        window.location.href = '/addemail.php';
                    }, 2000);
                } else {
                    // Alert for failed password change
                    alert(data.message);
                }
            },
            error: function(xhr, status, error) {
                if (xhr.status === 401) {
                    alert(xhr.responseText);
                }
            }
        });
    });
});