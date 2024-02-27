@extends('layouts.appauth')
@section('title', 'Birthdate Verification')
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
    <form action="{{ route('birthdate.verify') }}" method="post">
        @csrf
        @method('POST')
        <div class="container d-flex justify-content-center">
            <div class="col-lg-12 w-100">
                <h2 class="me-md-5 mb-0">Verify Birthdate</h2>
                {{-- <small>One more step ahead! 💪</small> --}}
                
                <div class="row justify-content-center" >
                    <div class="col-lg-12 col-md-12 mx-5 my-2">
                        <label for="birth_date">Birthdate</label>
                        <input type="date" class="form-control" name="birth_date">
                        <div class="text-danger">
                            @if($errors->has('birthdate'))
                                <small>{{ $errors->first('birthdate') }}</small>
                            @endif
                        </div>
                        
                        <button type="submit" class="btn btn-success mt-3 w-100">Verify</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection