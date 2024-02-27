@extends('layouts.app')
@section('title', 'Members Loan')
@section('css')
<link rel="stylesheet" href="{{ asset('css/repositories.css') }}">
@endsection
@section('content')

<div id="page-content-wrapper">
  <div class="spinner-body position-absolute top-50 start-50 translate-middle z-3">
    <div class=" spinner"></div>
  </div>
  <div class="container-fluid xyz pe-0">
    <div class="row">
      <div class="col-lg-12">
        <h1>
          Members Loan Requests
        </h1>
        <div class="row" style="margin-top: 2em;">
          <!-- Table -->
          <div class="row pe-0">
            <div class="col-lg-12">
              <div class="row">
                <div class="col w-100">
                  <div class="col-lg-12">
                    <!-- Whole top bar -->
                    <div class="row d-flex align-items-center justify-content-between flex-lg-row">
                      <div class="col-lg-auto border d-flex justify-content-center px-1 me-2" style="border-radius: 10px;">
                        <div class="py-1 w-100 d-flex justify-content-between ">
                          <div class="col-auto pt-0 mx-1 w-auto">
                            <button class="btn text-primary filter-btn fw-medium" data-status="all">All <small>{{ $allCount }}</small></button>
                          </div>
                          <div class="col-auto pt-0 me-1 w-auto">
                            <button class="btn text-primary-emphasis filter-btn fw-medium" data-status="pending">Pending <small>{{ $pendingCount }}</small></button>
                          </div>
                          <div class="col-auto pt-0 me-1 w-auto">
                            <button class="btn text-success filter-btn fw-medium" data-status="approved">Approved <small>{{ $approvedCount }}</small></button>
                          </div>
                          <div class="col-auto pt-0 me-1 w-auto">
                            <button class="btn text-danger filter-btn fw-medium" data-status="rejected">Rejected <small>{{ $rejectedCount }}</small></button>
                          </div>
                        </div>
                      </div>
                      <!-- Search bar -->
                      <div class="col-lg-4 mt-3 mt-lg-0 w-100-lg px-0" id="search-top-bar">
                        <div class="">
                          <input class="form-control border rounded" type="text" placeholder="Search" id="search-input">
                        </div>
                      </div>
                      <!-- /Search bar -->
                    </div>
                  </div>
                  </div>
                </div>
                <!-- /Whole top bar -->
                <div class="row ps-0 my-3">
                  <div class="col-lg-3 px-0">
                    <label class="form-label ps-0" for="sort">Sort by:</label>
                    <select id="sort" name="sort" class="form-control">
                        <option value="desc" selected>Date (DESC)</option>
                        <option value="asc">Date (ASC)</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="card">
                <div class="mt-0 p-1 pt-2 table table-responsive">
                  <table id="loan-applications" class="table table-hover table-bordered" style="font-size: large;">
                    <thead>
                      <tr>
                        <th class='fw-medium'>#</th>
                        <th class='fw-medium'>Reference number</th>
                        <th class='fw-medium'>Account Number</th>
                        <th class='fw-medium'>Name</th>
                        <th class='fw-medium'>College/Dept</th>
                        <th class='fw-medium'>Type</th>
                        <th class='fw-medium'>Date of applying</th>
                        <th class='fw-medium'>Requested Amount</th>
                        <th class='fw-medium'>Finance Disbursed</th>
                        <th class='fw-medium'>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($loanApplications as $loanApplication)
                        <tr>
                          <td> {{ $loop->iteration }}</td>
                          <td><a href="{{ route('admin.loan.application', $loanApplication->loan_reference) }}" class="text-decoration-none text-primary">{{ $loanApplication->loan_reference }}</a></td>
                          <td> {{  $loanApplication->user->account_number}}</td>
                          <td> {{ $loanApplication->customer_name }}</td>
                          <td> {{ $loanApplication->college }}</td>
                          <td> {{ $loanApplication->loan_type }}</td>
                          <td> {{ \Carbon\Carbon::parse($loanApplication->application_date)->format('m/d/Y') }}</td>
                          <td> Php {{ number_format($loanApplication->financed_amount, 2) }}</td>
                          <td> Php {{ number_format($loanApplication->finance_charge, 2) }}</td>
                          <td class="@if($loanApplication->application_status == 'pending') text-primary-emphasis fw-medium @elseif($loanApplication->application_status == 'approved') text-success fw-medium @elseif($loanApplication->application_status == 'rejected') text-danger fw-medium @endif"> {{ ucfirst($loanApplication->application_status) }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>  
                  
              </div>
              </div>
             
                {{-- Pagination Links --}}
                {{-- @if ($loanApplications->hasPages())
                <div class="pagination-wrapper">
                  {{ $loanApplications->links() }}
                </div>
                @endif --}}
                <div id="pagination" class="pagination mt-3 flex-column">
                  <!-- Pagination links will be updated here by AJAX -->
              </div>
            </div>
        </div>
          <!-- /Table -->
      </div>
    </div>
  </div>
</div>

@section('script')
<script>
$(document).ready(function() {
    // Show preloader at the start
    showPreloader();

    var currentStatus = 'all'; // Default status
    var currentSort = $('#sort').val(); // Capture the initial sort value
    var query = $('#search-input').val(); // Use a global variable for the search query
    
    // Initial fetch for loans with preloader management
    fetchLoansByStatusAndSort(currentStatus, currentSort, query, 1, true); // Use the query variable

    // Event handler for filter buttons
    $('.filter-btn').on('click', function() {
        currentStatus = $(this).data('status');
        fetchLoansByStatusAndSort(currentStatus, currentSort, query); // Use the global query variable
    });

    // Event handler for the search input
    $('#search-input').on('keyup', function() {
        query = $(this).val(); // Update the global query variable
        fetchLoansByStatusAndSort(currentStatus, currentSort, query);
    });

    // Event handler for the sort select dropdown
    $('#sort').on('change', function() {
        currentSort = $(this).val();
        fetchLoansByStatusAndSort(currentStatus, currentSort, query); // Use the global query variable
    });

    // Separate event handler for pagination links
    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        handlePagination(currentStatus, currentSort, query, page); // Use the global query variable
    });
});

function fetchLoansByStatusAndSort(status, sort, query = '', page = 1, initialLoad = false) {
    setTimeout(function() {
        // Construct the URL with the current page
        var url = `/admin/loans/filter/${status}?sort=${sort}&search=${encodeURIComponent(query)}&page=${page}`;
        fetchAndUpdate(url, initialLoad);
    }, 100); // Delay of 500ms before fetching
}

function handlePagination(status, sort, query  = '', page) {
    // Show preloader here for pagination as well
    showPreloader();
    setTimeout(function() {
        // Construct the URL for pagination
        var url = `/admin/loans/filter/${status}?sort=${sort}&search=${encodeURIComponent(query)}&page=${page}`;
        fetchAndUpdate(url);
    }, 100); // Delay of 500ms before fetching
}

function fetchAndUpdate(url, initialLoad = false) {
    fetch(url)
        .then(response => response.json())
        .then(data => {
            document.querySelector("#loan-applications tbody").innerHTML = data.html;
            document.querySelector("#pagination").innerHTML = data.pagination; // Update pagination links
            if (initialLoad) {
                // Hide preloader after the initial load is complete
                hidePreloader();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (initialLoad) {
                // Hide preloader even if there's an error on initial load
                hidePreloader();
            }
        });
}

function showPreloader() {
    // Implement showing the preloader
    $('.spinner').show();
}

function hidePreloader() {
    // Implement hiding the preloader
    $('.spinner').hide();
    $('.spinner-body').hide();
}
</script>
@endsection
@endsection