@extends('layouts.app')
@section('title', 'Account')
@section('css')
  <link href="{{ asset('css/member/account/account.css') }}" rel="stylesheet">
  <link href="{{ asset('css/member/account/style.css') }}" rel="stylesheet">
  {{-- <link href="{{ asset('css/member/account/style.scss') }}" rel="stylesheet"> --}}
@endsection
@section('content')
<div id="page-content-wrapper">
    <div class="container-fluid xyz">
      <div class="row">
        <div class="col-lg-12">
          <h1>
            Account Overview
          </h1>
          <div class="container-wrapper-contents px-0 w-auto">
            <div class="mb-3">
              <div class="col mb-3 px-4 py-3 border border-dark-subtle rounded">
                <div class="row w-auto d-flex justify-content-between">
                      <div class="col-lg-3 pe-0 pt-0">
                        <div class="col-lg-11 d-flex align-items-center h-100 pe-3 ">
                          <div >
                            <p class="mb-1">Account Balance</p>
                            <span class="fw-normal fs-lg">₱{{ number_format($loanBalance, 2) }}</span>
                          </div>
                        </div>
                          
                      </div>
                      <div class="col-lg-2 pe-0 ps-auto ps-lg-0 pt-0 ">
                        <div class="col-lg-9 d-flex align-items-center h-100 ">
                          <div class="me-2">
                            <p class="mb-1">Remarks</p>
                            <span class="fw-normal fs-lg">{{ ucfirst($remarks) }}</span>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-2 pe-0 ps-auto ps-lg-2 pt-0 ">
                        <div class="col-lg-12 d-flex align-items-center h-100 ">
                          <div class="mx-0 mx-lg-2">
                              <p class="mb-1">Amount of Shares</p>
                              <span class="fw-normal fs-lg">₱{{ number_format($amount_of_shares, 2) }}</span>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3 d-flex ps-auto px-lg-3">
                        <div class="col-lg-12 d-flex align-items-center h-100 w-100">
                          <div class="me-2 pe-2">
                            <p class="mb-1">Latest Loan Application Status
                              @if($recentApplicationUpdateTimestamp)
                              <span data-bs-toggle="tooltip" data-bs-placement="top" title="Active for 
                              {{ $recentApplicationUpdateTimestamp->diffInDays(now()) > 0 ? $recentApplicationUpdateTimestamp->diffInDays(now()) . ' days' : 
                              ($recentApplicationUpdateTimestamp->diffInHours(now()) > 0 ? $recentApplicationUpdateTimestamp->diffInHours(now()) . ' hours' : 
                              $recentApplicationUpdateTimestamp->diffInMinutes(now()) . ' minutes') }}">
                                  <i class="bi bi-info-circle"></i>
                              </span>
                              @endif
                            </span></p>
                            <div id="loanStatusContainer" class="fw-normal">
                              @if($loanApplicationStatus == 'approved')
                                <span class="text-success fs-lg">{{ ucfirst($loanApplicationStatus) }}</span>
                              @elseif($loanApplicationStatus == 'rejected')
                                <span class="text-danger fs-lg">{{ ucfirst($loanApplicationStatus) }}</span>
                              @else
                                <span class="text-primary-emphasis fs-lg">{{ ucfirst($loanApplicationStatus) }}</span>
                              @endif
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
                </div>
            </div>
          </div>
              <!-- Reports -->
          <div class="col-md-12 w-100 mt-5">
              <div class="">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="pt-2 mb-0">Accounting Trails</h3>
                    <div class="dropdown">
                      <button type="button" class="btn btn-link p-0" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots"></i>
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><button class="dropdown-item" onclick="location.reload();">Refresh</button></li>
                      </ul>
                    </div>
                  </div>

                  <div class="table-wrapper t-scrollable">
                  <table class="table table-responsive table-striped table-hover">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Payment Reference No.</th>
                        <th>Loan Reference No.</th>
                        <th>Date</th>
                        <th>Amount Paid</th>
                        <th>Remarks</th>
                        <th>Notes</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($payments as $payment)
                        @foreach ($payment->payment_pivot as $pivot)
                          <tr >
                            <td>{{ $loop->parent->iteration }}</td>
                            <td>{{ $payment->reference_no }}</td>
                            <td>{{ $pivot->loanApplication->loan_reference }}</td>
                            <td>{{ $payment->created_at->format('M-d-Y') }}</td>
                            <td>{{ $payment->amount_paid }}</td>
                            <td>{{ $pivot->remarks }}</td>
                            {{-- <td>{{ $payment->note }}</td> --}}
                            @if($payment->note)
                              <td><button class='btn btn-primary btn-sm w-100' data-bs-toggle='modal' data-bs-target='#noteModal' data-note="{{ $payment->note }}">Note</button></td>
                            @else
                              <td></td>
                            @endif
                          </tr>
                        @endforeach
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-md-12 w-100 mt-5">
                <div class="">
                  <div class="d-flex justify-content-between align-items-center">
                      <h3 class="pt-2 mb-0">Loan Trails</h3>
                      <div class="dropdown">
                        <button type="button" class="btn btn-link p-0" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                          <li><button class="dropdown-item" onclick="location.reload();">Refresh</button></li>
                        </ul>
                      </div>
                    </div>

                    <div class="table-wrapper">
                    <table class="table table-striped table-hover ">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Loan Reference No.</th>
                          <th>Loan Type</th>
                          <th>Date Applied</th>
                          <th>Due Date</th>
                          <th>Loan Amount</th>
                          <th>Amount Disbursed</th>
                          <th>Installment Amount</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($loanApplications as $loanApplication)
                        @php
                          $dueDate = \Carbon\Carbon::now()->addMonths($loanApplication->time_pay)->format('Y-m-d');
                        @endphp  
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $loanApplication->loan_reference }}</td>
                          <td>{{ $loanApplication->loan_type }}</td>
                          <td>{{ $loanApplication->created_at->format('M-d-Y') }}</td>
                          <td>{{ \Carbon\Carbon::parse($dueDate)->format('M-d-Y') }}</td>
                          <td>{{ $loanApplication->financed_amount }}</td>
                          <td>{{ $loanApplication->finance_charge }}</td>
                          <td>{{ $loanApplication->monthly_pay }}/mo</td>
                        </tr>
                      @endforeach
                      </tbody>
                    </table>
                  </div>
              </div>
            </div>
          </div>
            <!-- /Reports -->
            <!-- Loan Trails -->
            
            <!-- /Loan Trails -->

            <!-- Modal -->
            <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="noteModalLabel">View note</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" id="noteModalBody">

                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- End modal -->
        </div>
      </div>
    </div>
  </div>
@endsection
@section('script')
<script>
  $(document).ready(function() {
    $('[data-bs-toggle="tooltip"]').tooltip();

    $('#noteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var note = button.data('note'); // Extract note from data-* attributes
        var modal = $(this);
        modal.find('.modal-body').text(note);
    });
});
</script>
@endsection