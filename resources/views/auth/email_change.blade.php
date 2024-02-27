@extends('layouts.appauth')
@section('title', 'Add Email')
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
    <form action="{{ route('email.change') }}" method="post">
        @csrf
        @method('POST')
        <div class="container d-flex justify-content-center">
            <div class="col-lg-12 w-100">
                <h2 class="me-md-5 mb-0">Add Email</h2>                
                <div class="row justify-content-center" >
                    <div class="col-lg-12 col-md-12 mx-5 my-2">
                        <label for="new_email">New Email</label>
                        <input type="email" class="form-control" placeholder="New email" name="email" id="new_email">
                        <div class="mt-2">
                            <label for="confirm_email">Confirm Email</label>
                            <input type="email" class="form-control" placeholder="Confirm email" name="confirm_email" id="confirm_email">
                            <div class="invalid-feedback">Emails do not match.</div>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger mt-3">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <button type="submit" class="btn btn-success mt-3 w-100">Verify</button>
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
    $('form').on('submit', function(e) {
        e.preventDefault(); // Prevent the form from being submitted
        var newEmail = $('#new_email').val();
        var confirmEmail = $('#confirm_email').val();
        if (newEmail != confirmEmail) {
            $('#confirm_email').addClass('is-invalid');
            $('#confirm_email').siblings('.invalid-feedback').text('Emails do not match.').show();
        } else {
            this.submit(); 
        }
    });
});
</script>
@endsection