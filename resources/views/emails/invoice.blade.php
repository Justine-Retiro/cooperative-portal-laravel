<!DOCTYPE html>
<html>
<head>
    <title>Invoice Email</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <div id="header" class="text-start d-flex align-items-center gap-3">
                    <img src="{{ asset('/assets/logo.png') }}" class="img-fluid" alt="Logo" width="100" height="25px">
                    <h2>Invoice Details</h2>
                </div>
                <hr>
                <h1 class="card-title">Payment Confirmation</h1>
                <p class="card-text">Dear Customer, {{ $name }}</p>
                <p class="card-text">We have received your payment and would like to thank you for your prompt payment.</p>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Payment Date</th>
                                <td>{{ $paymentDate }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Transaction Occurred</th>
                                <td>{{ $transaction }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Biller Name</th>
                                <td>Credit Cooperative</td>
                            </tr>
                            <tr>
                                <th scope="row">Payment Reference Number</th>
                                <td>{{ $referenceNumber }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Amount Paid</th>
                                <td>Php {{ $amountPaid }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Payment Remarks</th>
                                <td>{{ $remarks }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

