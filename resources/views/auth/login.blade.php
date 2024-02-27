@extends('layouts.appauth')
@section('title', 'Login')
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
    <form action="{{ route('login.user') }}" method="post">
        @csrf
        @method('POST')
        <div class="row px-4">
            <div class="col-lg-12 px-md-5 w-100">
                <h2 class="me-md-5 mb-0">Login</h2>
                @if(session()->has('visited'))
                    <small>Hi, Great to see you! 👋</small>
                @else
                    <small>Hi, Welcome back! 👋</small>
                    @php session(['visited' => true]); @endphp
                @endif
                <div class="row justify-content-center" >
                    <div class="col-lg-12 col-md-12 mx-5 my-2">
                        <div class="">
                            {{-- @if($errors->any())
                            <small class="text-danger">{{ $errors->first() }}</small>
                        @endif --}}
                        </div>
                        
                        <label for="account_number">Account Number</label>
                        <input type="text" class="form-control mb-2 @error('account_number') is-invalid @enderror" placeholder="Account Number" name="account_number" value="{{ old('account_number') }}" aria-label="Username" aria-describedby="addon-wrapping">
                        @error('account_number')
                            <div class="invalid-feedback mb-1">{{ $message }}</div>
                        @enderror
                        <label for="password">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" id="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="mt-2">
                            <input type="checkbox" id="show_password">
                            <label for="show_password">Show Password</label>
                        </div>
                        <a href="{{ route('forgot.password') }}" class="text-decoration-none" ><p class="pt-2 mb-0" >Forgot password</p></a>
                        <button class="btn btn-primary mt-3 w-100">Login</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@section('script')
<script>
$('#show_password').change(function() {
    if($(this).is(":checked")) {
        $('#password').attr('type', 'text');
    } else {
        $('#password').attr('type', 'password');
    }
});
</script>
@endsection