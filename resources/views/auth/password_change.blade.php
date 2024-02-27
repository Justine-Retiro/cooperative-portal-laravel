@extends('layouts.appauth')
@section('title', 'Change Password')
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
    <form action="{{ route('change.password') }}" method="post">
        @csrf
        @method('POST')
        <div class="row px-4">
            <div class="col-lg-12 px-md-5 w-100">
                <h2 class="me-md-5 mb-0">Change Password</h2>
                <div class="row justify-content-center" >
                    <div class="col-lg-12 col-md-12 mx-5 my-2">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control password_holder" placeholder="New Password" name="password" id="new_password" oninput="checkPasswordMatch()" required >
                        <div class="invalid-feedback">Password must contain at least one number, one special character, and be at least 8 characters long.</div>
                    
                        <label for="confirm_new_password">Confirm New Password</label>
                        <input type="password" class="form-control password_holder" placeholder="Confirm Password" name="confirm_new_password" id="confirm_new_password" oninput="checkPasswordMatch()" required>
                        <div class="invalid-feedback">Passwords do not match.</div>
                        <div class="mt-2">
                            <input type="checkbox" id="show_password">
                            <label for="show_password">Show Password</label>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3 w-100">Change Password</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@section('script')
<script>
window.checkPasswordMatch = function() {
    var password = $('#new_password').val();
    var confirmPassword = $('#confirm_new_password').val();
    var regex = /^(?=.*[0-9])(?=.*[\W_]?)[a-zA-Z0-9\W_]{8,}$/;

    // Validate the new password against the regex
    if (!regex.test(password)) {
        $('#new_password').addClass('is-invalid');
        $('#new_password').siblings('.invalid-feedback').text('Password must contain at least one number, one special character, and be at least 8 characters long.').show();
    } else {
        $('#new_password').removeClass('is-invalid');
        $('#new_password').siblings('.invalid-feedback').hide();
    }

    // Then, check if the new password matches the confirm password
    if (password !== confirmPassword) {
        $('#confirm_new_password').addClass('is-invalid');
        $('#confirm_new_password').siblings('.invalid-feedback').text('Passwords do not match.').show();
    } else {
        if(regex.test(confirmPassword)) {
            $('#new_password').removeClass('is-invalid');
            $('#new_password').siblings('.invalid-feedback').hide();

            $('#confirm_new_password').removeClass('is-invalid');
            $('#confirm_new_password').siblings('.invalid-feedback').hide();
        }
    }
};
$(document).ready(function() {
    // Toggle password visibility
    $('#show_password').change(function() {
        if($(this).is(":checked")) {
            $('.password_holder').attr('type', 'text');
        } else {
            $('.password_holder').attr('type', 'password');
        }
    });

    // Check password match and complexity

    // Validate password on input
    $('#new_password, #confirm_new_password').on('input', checkPasswordMatch);

    // Form submission handler
    $('form').submit(function(event) {
        checkPasswordMatch(); // Ensure validation runs before form submission
        var formValid = this.checkValidity();
        if (!formValid) {
            event.preventDefault(); // Prevent form submission if validation fails
            alert('Please correct the errors before submitting.');
        }
    });
});
</script>
@endsection