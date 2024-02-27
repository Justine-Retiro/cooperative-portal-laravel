@extends('layouts.app')
@section('title', 'Application')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/application.css') }}">
@endsection
@section('content')
<div id="page-content-wrapper">
    <div class="container-fluid xyz">
      <div class="row">
        <div class="container">
            <div class="col-lg-12">
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.loan.home') }}" class="text-decoration-none">Members Loan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Application</li>
                  </ol>
                </nav>
                <h2>
                  Member loan application
                </h2>
                <div class="row mt-1">
                <!-- Table Trails -->
                <div class="col-lg-12 px-3">
                  <h3>Loan user trails</h3>
                  {{-- @php
                    function next_sort_direction($currentDirection) {
                        $sortOrder = ['asc' => 'desc', 'desc' => 'custom', 'custom' => 'asc'];
                        return isset($sortOrder[$currentDirection]) ? $sortOrder[$currentDirection] : 'asc';
                    }
                  @endphp --}}
                  <div class="table-responsive" id="table-container">
                    <table  class="table table-hover table-bordered">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col" class="fw-semibold">Loan ID</th>
                                <th scope="col" class="fw-semibold">Customer name</th>
                                <th scope="col" class="fw-semibold">Loan type</th>
                                <th scope="col" class="fw-semibold">Date of applying</th>
                                <th scope="col" class="fw-semibold">Loan Amount</th>
                                <th scope="col" class="fw-semibold">Amount disbursed</th>
                                <th scope="col" class="fw-semibold">Status</th>
                                <th scope="col" class="fw-semibold">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pastLoanApplications as $loanApplication)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $loanApplication->loan_reference }}</td>
                                <td>{{ $loanApplication->customer_name }}</td>
                                <td>{{ $loanApplication->loan_type }}</td>
                                <td>{{ \Carbon\Carbon::parse($loanApplication->application_date)->format('m/d/Y') }}</td>
                                <td>{{ $loanApplication->financed_amount }}</td>
                                <td>{{ $loanApplication->finance_charge }}</td>
                                <td>{{ ucfirst($loanApplication->application_status) }}</td>
                                <td>{{ ucfirst($loanApplication->remarks) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Links -->
                <div id="paginationLinks">
                    {{ $pastLoanApplications->appends(request()->except('page'))->links() }}
                </div>
              </div>
                  <!-- Table -->
                  <div class="container mb-3">
                    <div class="card p-3">
                        <span>Account Number: <span  class="fw-medium">{{ $currentLoanApplication->user->account_number }}</span></span>
                        <span>Loan Reference Number: <span class="fw-medium">{{ $currentLoanApplication->loan_reference }}</span></span>
                        <span>Status: <span class="@if($currentLoanApplication->application_status == 'pending') text-primary-emphasis @elseif($currentLoanApplication->application_status == 'approved') text-success @elseif($currentLoanApplication->application_status == 'rejected') text-danger @endif fw-medium">{{ ucfirst($currentLoanApplication->application_status) }}</span></span>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-lg-4">
                        <div class="mb-3">
                          <label for="name" class="form-label">Name</label>  
                          <input type="text" class="form-control" id="name" value="{{$currentLoanApplication->customer_name}}" placeholder="" disabled readonly>
                            
                        </div>
                        <div class="mb-3">
                          <label for="college">College/Dept</label>  
                          <input type="text" class="form-control" id="college" placeholder="College/Dept" value="{{$currentLoanApplication->college}}" disabled readonly>
                            
                        </div>
                        <div class="mb-3">
                          <label for="contact">Contact No.</label>  
                          <input type="text" class="form-control" id="contact" placeholder="Contact No." value="{{$currentLoanApplication->contact_num}}" disabled readonly>
                            
                        </div>
                        <div class="mb-3">
                          <label for="dob">Date of Birth</label>
                          <input type="text" class="form-control" id="dob" placeholder="Date of Birth" value="{{ \Carbon\Carbon::parse($currentLoanApplication->birth_date)->format('m-d-Y') }}" disabled readonly>
      
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3">  
                          <label for="age">Age</label>
                          <input type="number" class="form-control" id="age" placeholder="Age" value="{{$currentLoanApplication->age}}" disabled readonly>
                            
                        </div>
                        <div class="mb-3">
                          <label for="doe">Date of Employed</label>  
                          <input type="text" class="form-control" id="doe" placeholder="Date of Employed" value="{{ \Carbon\Carbon::parse($currentLoanApplication->date_employment)->format('m-d-Y') }}" disabled readonly>
                            
                        </div>
                        <div class="mb-3">
                          <label for="retirement">Year of Retirement</label>  
                          <input type="number" class="form-control" id="retirement" placeholder="Year of Retirement" value="{{$currentLoanApplication->retirement_year}}" disabled readonly>
                            
                        </div>
                        <div class="mb-3">
                          <label for="position">Work Position</label>  
                          <input type="text" class="form-control" id="position" placeholder="Work Position" value="{{$currentLoanApplication->work_position}}" disabled readonly>
                            
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3" >
                            <label for="loan_type">Loan Type</label>
                            <input for="loan_type" class="form-control" id="loan_type" type="text" value="{{$currentLoanApplication->loan_type}}" disabled readonly >
                        </div>
                        <div class="mb-3">
                          <label for="doa">Date of Application</label>  
                          <input type="date" class="form-control" id="doa" placeholder="Date of Application" value="{{$currentLoanApplication->application_date}}" disabled readonly>
                        </div>
                        <div class="mb-3">
                          <label for="amount">Loan Amount</label>  
                          <input type="text" class="form-control" id="amount" value="{{$currentLoanApplication->financed_amount}}" disabled readonly>
                        </div>
                        <div class="mb-3">
                          <label for="note">Note</label>  
                          <input type="text" class="form-control" id="noteInput" name="noteInput" placeholder="Note">
                        </div>
                        <div class="mb-3 float-end">
                          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loan_details">
                            More details
                          </button>
                        </div>
                    </div>
                    <div class="col-lg-12">
                      <br>
                      <h4 >Terms and agreement</h4>
                      <p>I hereby authorize the NEUST Community Credit Cooperative/NEUST Cashier to deduct
                        the monthly amortization of my loan from my pay slip. 
                        I AGREE THAT ANY LATE PAYMENT
                        WILL BE SUBJECTED TO A PENALTY OF 3% PER MONTH OF DELAY. Furthermore, default in
                        payments for three (3) months will be ground for the coop to take this matter into court and the
                        balance should be due and demandable.</p>
                        <div class="mb-3">
                          <!-- <label for="signature">Upload picture of signature</label>  
                          <input type="file" class="form-control" id="signature" onchange="previewImage(event)"> -->
                          <!-- Modal for image viewer -->
                          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#applicant_sign">
                            Signature Picture
                          </button>
      
                          <!-- Modal -->
                          <div class="modal fade" id="applicant_sign" tabindex="-1" aria-labelledby="#applicant_signModal" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h1 class="modal-title fs-5" id="applicant_signModal">Signature picture</h1>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @foreach($mediaItems as $mediaItem)
                                        @php
                                            $correctPath = Str::replaceFirst('public/', '', $mediaItem->signature);
                                        @endphp
                                        {{-- Use the corrected path with the "storage/" prefix --}}
                                        <img src="{{ asset('storage/' . $correctPath) }}" alt="Media Item" style="width: 100%;">
                                    @endforeach
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- End of Modal -->
      
                          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#takehome_receipt">
                            Take homepay picture
                          </button>
      
                          <!-- Modal -->
                          <div class="modal fade" id="takehome_receipt" tabindex="-1" aria-labelledby="takehome_receiptLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h1 class="modal-title fs-5" id="takehome_receipt">Take home pay Receipt</h1>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @foreach($mediaItems as $mediaItem)
                                        @php
                                            $correctPath = Str::replaceFirst('public/', '', $mediaItem->take_home_pay);
                                        @endphp
                                        {{-- Use the corrected path with the "storage/" prefix --}}
                                        <img src="{{ asset('storage/' . $correctPath) }}" alt="Media Item" style="width: 100%;">
                                    @endforeach
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
      
                          <!-- /Modal -->
                          <!-- Button trigger modal -->
                          @php
                            $dueDate = \Carbon\Carbon::now()->addMonths($currentLoanApplication->time_pay)->format('Y-m-d');
                          @endphp  
      
                          <!-- Modal -->
                          <div class="modal fade" id="loan_details" tabindex="-1" aria-labelledby="loan_detailsLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h1 class="modal-title fs-5" id="loan_detailsLabel">More details</h1>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                  <div class="">
                                    <div class="row">
                                        <div class="col-lg-12 d-flex justify-content-between">
                                            <p>Loan Amount </p> <p>Php<span> {{ number_format($currentLoanApplication->financed_amount, 2) }}</span> </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 d-flex justify-content-between">
                                            <p>Amount disbursed&nbsp;
                                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="Amount to be given">
                                              <i class="bi bi-info-circle"></i>
                                            </span></p> 
                                            <p>Php<span> {{ number_format($currentLoanApplication->finance_charge, 2) }}</span> </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 d-flex justify-content-between">
                                            <p>Term</p> <p>{{ $currentLoanApplication->time_pay }} {{ $currentLoanApplication->time_pay > 1 ? 'Months' : 'Month' }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 d-flex justify-content-between">
                                            <p>Due date&nbsp;
                                              <span data-bs-toggle="tooltip" data-bs-placement="top" title="Estimate if approved">
                                              <i class="bi bi-info-circle"></i>
                                            </span></p> 
                                            <p>{{ \Carbon\Carbon::parse($dueDate)->format('m/d/Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 d-flex justify-content-between">
                                            <p>Interest </p> <p>1%</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-lg-12 d-flex justify-content-between">
                                          <p>Monthly Installment </p> <p>Php {{$currentLoanApplication->monthly_pay}}</p>
                                      </div>
                                    </div>
                                  </div>
                                  
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                              </div>
                            </div>
                          </div>
      
                          <!-- /Modal -->
      
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-lg-12">
                            {{-- Assuming $currentLoanApplication holds the loan application being viewed --}}
                            <div class="actions d-flex justify-content-end gap-2">
                                @php
                                    $user = auth()->user();
                                @endphp
                                

                                @if($currentLoanApplication->checkapprovals && !$currentLoanApplication->checkapprovals->book_keeper && $currentLoanApplication->application_status == 'pending')
                                    {{-- For Permission Level 3 Users --}}
                                    @if($user->hasPermission(3))
                                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmationModal">Approve</button>
                                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmationRejectModal">Reject</button>
                                        <div class="modal fade" id="confirmationModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="confirmationModalLabel">Confirm Action</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <p>Are you sure you want to approve this loan application?</p>
                                                    <p>This action cannot be undone.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-primary" id="confirmSubmit" onclick="document.getElementById('approveForm').submit();">Confirm</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="confirmationRejectModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="confirmationModalLabel">Confirm Action</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to reject this loan application?</p>
                                                        <p>This action cannot be undone.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="button" type="submit" class="btn btn-primary" id="confirmSubmit" onclick="document.getElementById('rejectForm').submit();">Confirm</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <form id="approveForm" action="{{ route('admin.loan.approveByLevel3', $currentLoanApplication->loan_reference) }}" method="POST">
                                            @csrf
                                            <input type="hidden" class="form-control" id="note" name="note" placeholder="Note">
                                        </form>
                                        <form id="rejectForm" action="{{ route('admin.loan.rejectByLevel3', $currentLoanApplication->loan_reference) }}" method="POST">
                                            @csrf
                                            <input type="hidden" class="form-control" id="note" name="note" placeholder="Note">
                                        </form>
                                    @endif
                                @endif
                                @if($currentLoanApplication->checkapprovals && (!$currentLoanApplication->checkapprovals->book_keeper || !$currentLoanApplication->checkapprovals->general_manager) && $currentLoanApplication->application_status == 'pending')
                                    {{-- For Permission Level 1 Users --}}
                                    @if($user->hasPermission(1) && $bookKeeperApproved )
                                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmationModal">Approve</button>
                                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmationRejectModal">Reject</button>
                                            <div class="modal fade" id="confirmationModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered ">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="confirmationModalLabel">Confirm Action</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                        <p>Are you sure you want to approve this loan application?</p>
                                                        <p>This action cannot be undone.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="button" class="btn btn-primary" id="confirmSubmit" onclick="document.getElementById('approveForm').submit();">Confirm</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="confirmationRejectModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="confirmationModalLabel">Confirm Action</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to reject this loan application?</p>
                                                            <p>This action cannot be undone.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="button" type="submit" class="btn btn-primary" id="confirmSubmit" onclick="document.getElementById('rejectForm').submit();">Confirm</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>    
                                    
                                        <form id="approveForm" action="{{ route('admin.loan.finalAcceptance', $currentLoanApplication->loan_reference) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="due_date" value="{{ \Carbon\Carbon::parse($dueDate)->format('Y-m-d') }}">
                                            <input type="hidden" class="form-control" id="note" name="note" placeholder="Note">
                                        </form>
                                        <form id="rejectForm" action="{{ route('admin.loan.rejectByLevel1', $currentLoanApplication->loan_reference) }}" method="POST">
                                            @csrf
                                            
                                            <input type="hidden" class="form-control" id="note" name="note" placeholder="Note">
                                        </form>
                                    @endif
                                @endif
                            </div>
                          </div>
                        </div>
                    </div>
      
                  </div>
                  <!-- /Table -->
              </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
$(document).ready(function(){
  $('[data-bs-toggle="tooltip"]').tooltip();

  $('#noteInput').on('input', function() {
    $('#note').val($(this).val());
  });
});
$(document).ready(function(){
    var lastClickTime = 0;
    const throttleTime = 500; // Adjusted throttle time

    // Initialize with the first state for application_status
    var sortStates = {
        'application_status': 'pending' // Initialize with the first state
    };

    $(document).on('click', '.sort-link', function(e) {
        e.preventDefault();
        var currentTime = new Date().getTime();
        if (currentTime - lastClickTime < throttleTime) {
            console.log("Click throttled.");
            return;
        }
        lastClickTime = currentTime;

        var $this = $(this);
        var sortColumn = $this.data('sort');

        // Ensure sortStates has an entry for this column; initialize if not
        if (!sortStates.hasOwnProperty(sortColumn)) {
            sortStates[sortColumn] = 'asc'; // Default to ascending for new columns
        }

        // Update the sort state based on the current state
        if(sortColumn === 'application_status') {
            // Custom cycle for application_status
            if(sortStates[sortColumn] === 'pending') {
                sortStates[sortColumn] = 'approved';
            } else if(sortStates[sortColumn] === 'approved') {
                sortStates[sortColumn] = 'rejected';
            } else if(sortStates[sortColumn] === 'rejected') {
                sortStates[sortColumn] = 'pending';
            }
        } else {
            // For other columns, toggle between 'asc' and 'desc'
            sortStates[sortColumn] = sortStates[sortColumn] === 'asc' ? 'desc' : 'asc';
        }

        var sortDirection = sortStates[sortColumn]; // Use the updated state as the direction

        var pathArray = window.location.pathname.split('/');
        var loanReferenceIndex = pathArray.indexOf('application') + 1;
        var loanReference = pathArray[loanReferenceIndex];
        var ajaxUrl = `/admin/loan/application/${loanReference}?sort=${sortColumn}&direction=${sortDirection}`;

        $.ajax({
            url: ajaxUrl,
            type: 'GET',
            success: function(response) {
                $('#table-container table tbody').empty().html(response.table);
            },
            error: function(xhr, status, error) {
                console.error("An error occurred: " + status + " " + error);
            }
        });
    });
});
</script>
@endsection