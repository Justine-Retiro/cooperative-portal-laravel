$(document).ready(function() {
    // Check if there's a toastr message in the cookie
    var toastrMessage = getCookie('toastr');
    if (toastrMessage) {
        // If there is, show the toastr and delete the cookie
        toastr.error(decodeURIComponent(toastrMessage));
        var now = new Date();
        now.setTime(now.getTime() - 1);
        document.cookie = 'toastr=; expires=' + now.toUTCString() + '; path=/;';
    }
    
    $('form').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: '/globalApi/resetpassword.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status === 'success') {
                    document.cookie = 'toastr=Successfully logged in; path=/;';
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
                }
            }
        });
    });
});
// Function to get a cookie by name
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}