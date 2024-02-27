@extends('layouts.appauth')
@section('title', 'Code Verification')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection 
@section('content')
<div class="p-2">
    <div class="row-auto">
        <div class="col-auto">
            <img class="img-fluid" src="{{ asset('assets/logo.png') }}" alt="" width="10%" height="auto">
            <span>NEUST Cooperative Partners </span>
        </div>
    </div>
</div>
<div class="container-fluid body-login d-flex flex-column justify-content-center align-items-center">
    <form action="{{ route('email.verify.code') }}" method="post">
        @csrf
        @method('POST')
        <div class="container d-flex justify-content-center">
            <div class="col-lg-12 w-100">
                <h2 class="me-md-5 mb-1">Email Verification</h2>
                    <p class="mb-0">  
                        <small> Did not receive the code? Check your spam folder. </small>
                    </p>

                <div class="row justify-content-center">
                    <div class="col-lg-12 my-1">
                        <label class="mb-" for="code">Enter the verification code</label>
                        <input type="text" class="form-control mb-1" placeholder="Verification code" name="code" id="code">
                        <input type="hidden" id="resend_countdown" value="2">
                        <small id="success-message" class="text-success"></small>
                        <small id="error-message" class="text-danger"></small>
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
@endsection
@section('script')
<script>
    
$(document).ready(function() {
    if('{{ session()->has('success') }}') {
        $('#success-message').show().text('{{ session()->get('success') }}').delay(20000).fadeOut();
    }
    var timer;
    var countdown = 2;
    startResendTimer();

    $('#resend_code').click(function() {
        if ($(this).prop('disabled')) {
            return; 
        }
        $.ajax({
            url: '/change-email/resend-verification-code',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                $('#success-message').text(response.message).show().delay(20000).fadeOut(); 
                startResendTimer(); // Restart the timer after resending
            },
            error: function() {
                $('#error-message').text(response.message).show().delay(20000).fadeOut(); 
            }
        });
    });

    function startResendTimer() {
        var countdown = 2;
        $('#resend_code').text('Resend in ' + countdown + 's').prop('disabled', true).css('pointer-events', 'none');

        timer = setInterval(function() {
            countdown--;
            if (countdown > 0) {
                $('#resend_code').text('Resend in ' + countdown + 's');
            } else {
                clearInterval(timer);
                $('#resend_code').prop('disabled', false).css('pointer-events', 'auto');
                $('#resend_code').text('Resend');
            }
        }, 1000);
    }
});
</script>
@endsection