$(document).ready(function() {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    // Check if there's a toastr message in the session
    // var urlParams = new URLSearchParams(window.location.search);
    // var toastrMessage = urlParams.get('toastr');
    // if (toastrMessage) {
    //     // If there is, show the toastr
    //     toastr.error(decodeURIComponent(toastrMessage));
    // }
    $('form').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: '/globalApi/verifybirthdate.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    toastr.success('Successfully logged in! Redirecting...'); // Adjust the message as needed
                    setTimeout(function() {
                        switch (data.role) {
                            case 'admin':
                                if (data.account_status === 'Active') {
                                    window.location.href = '/admin/dashboard/dashboard.php';
                                } else {
                                    window.location.href = '/';
                                }
                                break;
                            case 'master':
                                if (data.account_status === 'Active') {
                                    window.location.href = '/master/dashboard/dashboard.php';
                                } else {
                                    window.location.href = '/';
                                }
                                break;
                            case 'mem':
                                if (data.account_status === 'Active') {
                                    window.location.href = '/member/dashboard/dashboard.php';
                                } else {
                                    window.location.href = '/';
                                }
                                break;
                            default:
                                window.location.href = '/';
                        }
                    }, 2000);
                } else if (data.status === 'fail') {
                    $('#birthdateAlert').text(data.message).removeClass('d-none');
                } else if (data.status === 'redirect') {
                    toastr.error('Too many failed attempts. Please log in again.');
                    setTimeout(function() {
                        window.location.href = '/index.php';
                    }, 2000); // Delay of 2 seconds
                } else if (data.status === '!email') {
                    toastr.error(data.message);
                    setTimeout(function() {
                        window.location.href = '/addemail.php';
                    }, 2000); // Delay of 2 seconds
                }
            }
        });
    });
});