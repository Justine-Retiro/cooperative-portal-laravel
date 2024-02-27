<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
    #header{
        border-bottom: 1px solid #CCCCCC;
    }
    table, th, td {
            border: none;
        }
    @media (max-width: 768px) {
        .card {
            width: 90% !important; /* Adjust card width on smaller screens */
        }
        .img-fluid {
            max-width: 80px; /* Adjust logo size on smaller screens */
            height: auto;
        }
        h2 {
            font-size: 16px; /* Adjust heading size on smaller screens */
        }
        .btn-lg {
            padding: .5rem 1rem; /* Adjust button size on smaller screens */
            font-size: .875rem;
        }
    }
    </style>
</head>
<body>
    <div class="container py-3">
        <div class="card text-center mx-auto" style="width: 50%;">
            <div class="card-body">
                <div class="">
                    
                </div>
                <table class="table">
                    <tbody>
                        <tr id="header">
                            <td colspan="2" class="text-start d-flex align-items-center gap-3"><img src="{{ asset('/assets/logo.png') }}" class="img-fluid" alt="Logo" width="100" height="25px"><h2>Email Verification</h2></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-start">
                                <p class="mb-0">Hello, {{ $name }}</p>
                                <p class="mb-0">Please complete the change password process for the Cooperative Portal by entering the code provided below:</p>
                                <div class="text-center d-flex flex-column justify-content-center w-auto my-4">
                                    <div class="d-flex justify-content-center mb-3">
                                        <span class="border p-3 fs-1 w-auto">{{ $verificationCode }}</span>
                                    </div>
                                </div>
                                <p class="mb-0">If you did not request this, no further action is required.</p>
                                <p class="mb-0">Best Regards,<br>Credit Cooperative Partners</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
            <div class="text-start mt-4">
                <small class="text-muted">This message was sent to <a href="mailto:{{ $email }}">{{ $email }}</a> and intended for {{ $name }}. <br>If you have questions or complaints, please contact us.</small>
                <br>
                <small class="text-muted">© {{ date('Y') }} Credit Cooperative Partners. All rights reserved.</small>
            </div>
        </div>
    </div>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
