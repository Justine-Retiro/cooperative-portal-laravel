@extends('layouts.app')
@section('title', 'Repositories')
@section('css')
<link rel="stylesheet" href="{{ asset('css/repositories.css') }}">
@endsection
@section('content')
<div id="page-content-wrapper">
    <div class="container-fluid xyz">
        <div class="row">
            <div class="col-lg-12">
                <h1>Members Details</h1>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.repositories') }}" class="text-decoration-none">Repositories</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add member</li>
                </ol>
                </nav>
                <div class="row mt-1">
                    <div class="row my-2" id="top-nav">
                        <div class="col-lg-12 d-flex flex-column flex-md-row gap-3 flex-start align-items-center">
                            <p class="mb-1">Account Number</p>
                            <!-- Auto generated -->
                            <div class="d-flex justify-content-between">
                                <div id="acc-number" class="p-1 ps-3 d-flex align-items-center">{{ $account_number }}
                                    <button class="btn" onclick="copyToClipboard('#acc-number')"><i class="bi bi-clipboard"></i></button>
                                </div>
                            </div>
                            
                            <p class="mb-1">Account default password</p>
                            <!-- Auto generated -->
                            <div class="d-flex justify-content-between">
                                <div id="acc-password" class="p-1 ps-3 d-flex align-items-center">{{ $password }}
                                    <button class="btn" onclick="copyToClipboard('#acc-password')"><i class="bi bi-clipboard"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <!-- Table -->
                            <form id="memberForm" action="{{ route('admin.store-repo') }}" method="post">
                                @csrf
                                @method('POST')                  
                                <div class="container mt-3">
                                <input type="hidden" name="account_number" value="{{ $account_number }}">
                                <input type="hidden" name="password" value="{{ $password }}">
                                
                                <!-- Row 1 -->
                                <div class="row mb-3">
                                <div class="col-lg-4">
                                    <label for="lastName">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" id="last_name" required>
                                </div>
                                <div class="col-lg-4">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" class="form-control" name="middle_name" id="middle_name" required>
                                </div>
                                <div class="col-lg-4">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" name="first_name" id="first_name" required>
                                </div>
                                </div>
                                
                                <!-- Row 2 -->
                                <div class="row mb-3">
                                    <div class="col-lg-4">
                                        <label for="city_address">City Address</label>
                                        <input type="text" class="form-control" name="city_address" id="city_address" required>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="provincial_address">Provincial Address</label>
                                        <input type="text" class="form-control" name="provincial_address" id="provincial_address" required>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="phone_num">Phone Number</label>
                                        <input type="number" class="form-control" name="phone_num" id="phone_num" oninput="if(this.value.length > 11) { alert('Phone number cannot exceed 11 digits.'); this.value = this.value.slice(0,11); }" required>
                                    </div>
                                </div>
                                
                                <!-- Row 3 -->
                                <div class="row mb-3">
                                    <div class="col-lg-4">
                                        <label for="civil_status">Civil Status</label>
                                        <select class="form-control" name="civil_status" id="civil_status">
                                        <option select>Select one</option>  
                                        <option>Single</option>
                                        <option>Married</option>
                                        <option>Divorced</option>
                                        <option>Widowed</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="spouse_name">Name of Spouse</label>
                                        <input type="text" class="form-control" name="spouse_name" id="spouse_name" required>
                                    </div>
                                    <div class="col-lg-4">
                                        <label for="position">Work Position</label>
                                        <input type="text" class="form-control" name="position" id="position" required>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                <div class="col-lg-4">
                                    <label for="citizenship">Citizenship</label>
                                    <input type="text" class="form-control" name="citizenship" id="citizenship" required>
                                </div>
                                <div class="col-lg-4">
                                    <label for="birth_date">Date of Birth</label>
                                    <input type="date" class="form-control" name="birth_date" id="birth_date" required>
                                </div>
                                <div class="col-lg-4">
                                    <label for="birth_place">Place of Birth</label>
                                    <input type="text" class="form-control" name="birth_place" id="birth_place" required>
                                </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-lg-4">
                                    <label for="taxID_num">Tax Identification Number</label>
                                    <input type="text" class="form-control" name="taxID_num" id="taxID_num" required>
                                    </div>
                                    <div class="col-lg-4">
                                    <label for="mailing_address">Mailing Address</label>
                                    <input type="text" class="form-control" name="mailing_address" id="mailing_address" required>
                                    </div>
                                    <div class="col-lg-4">
                                    <label for="date_employed">Date of Employment</label>
                                    <input type="date" class="form-control" name="date_employed" id="date_employed" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-lg-4">
                                    <label for="natureOf_work">Nature of Work</label>
                                    <select class="form-control" name="natureOf_work" id="natureOf_work" required>
                                        <option>Teaching</option>
                                        <option>Non-Teaching</option>
                                        <option>Others</option>
                                    </select>
                                    </div>
                                    <div class="col-lg-4">
                                    <label for="account_status">Account Status</label>
                                    <select class="form-control" name="account_status" id="account_status" required>
                                        <option>Active</option>
                                        <option>Not Active</option>
                                    </select>
                                    </div>
                                    <div class="col-lg-4">
                                    <label for="amountOf_share">Amount of shares</label>
                                    <input type="text" class="form-control" name="amountOf_share" id="amountOf_share" required>
                                    </div>
                                </div>
                                <button class="btn btn-success btn-lg mt-3" id="toast-submit" type="submit" style="float: right;">Save</button>
                            </form>
                            <!-- /Table -->
                        </div>
                    

                        <!-- Toaster -->
                        <div class="toast-container position-fixed bottom-0 end-0 p-3">
                            <div id="clipboard" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="toast-header">
                                <span class="rounded bg-success px-1 me-2"><i class="bi bi-check2 text-success"></i></span>
                                <strong class="me-auto">Clipboard</strong>
                                <small id="timestamp"></small>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                                <div class="toast-body bg-light">
                                    Copied successfully
                                </div>
                            </div>
                        </div>
                        <!-- /Toaster -->

                        <!-- Toaster -->
                        <div class="toast-container position-fixed bottom-0 end-0 p-3">
                            <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="toast-header">
                                <span class="rounded bg-success px-1 me-2"><i class="bi bi-check2 text-success"></i></span>
                                <strong class="me-auto">Form</strong>
                                <small id="timestamp"></small>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                                <div class="toast-body">
                                    Entry has been successfully recorded
                                </div>
                            </div>
                        </div>
                        <!-- /Toaster -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('script')
<script>
$(document).ready(function() {
  $('#civil_status').change(function() {
    if ($(this).val() === 'Single') {
      $('#spouse_name').val('None');
    } else {
      $('#spouse_name').val('');
    }
  });
});

function copyToClipboard(elementId) {
    var copyText = document.querySelector(elementId).innerText;
    var textarea = document.createElement("textarea");
    textarea.textContent = copyText;
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand("copy");
    document.body.removeChild(textarea);
    var toastElement = new bootstrap.Toast(document.getElementById('clipboard'));
    toastElement.show();
}

</script>
@endsection
@endsection