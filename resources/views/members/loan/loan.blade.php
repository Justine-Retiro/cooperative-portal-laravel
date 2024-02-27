@extends('layouts.app')
@section('title', 'Loan')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/member/account/account.css') }}">
    <link rel="stylesheet" href="{{ asset('css/member/account/style.css')}}">
@endsection
@section('content')



<div class="page-content-wrapper">
    <div class="container-fluid xyz p-4">
        <div class="row d-flex align-items-center">
            <div class="col-lg-9 py-3">
                <h1 id="user-greet">Loan Overview</h1>
            </div>
        </div>
        <div class="row d-flex">
            <div class="w-100 d-flex flex-column flex-lg-row border  rounded px-0 p-2">
                <!--Apply btn-->
                <div class="col-lg-auto px-3 d-flex align-items-center border-md-end-0 border-end">
                  <a class="btn btn-primary my-2 " href="{{ route('member.loan.apply') }}" role="button">
                      <i class="bi bi-credit-card-2-front"></i> &nbsp;Apply loan
                  </a>
                </div>
                <div class="col-lg-4 h-auto w-auto mx-3 d-flex align-items-center" style="border-radius: 10px;">
                    <div class="row d-flex align-items-center">
                        <div class="col-md-3 pt-0 w-auto" >
                            <button class="btn text-primary-emphasis fw-medium" >All <small>{{$totalLoans}}</small></button>
                        </div>
                        <div class="col-md-3 pt-0 w-auto" >
                            <button class="btn text-primary-emphasis fw-medium" >Pending <small>{{$totalPendingLoans}}</small></button>
                        </div>
                        <div class="col-md-3 pt-0 w-auto" >
                            <button class="btn text-success fw-medium" >Accepted <small>{{$totalApprovedLoans}}</small></button>
                        </div>
                        <div class="col-md-3 pt-0 w-auto" >
                            <button class="btn text-danger fw-medium" >Rejected <small>{{$totalRejectedLoans}}</small></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!--Main Content-->
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
              <table class="table table-hover table-striped ">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Loan Reference</th>
                    <th>Loan Type</th>
                    <th>Date Applied</th>
                    <th>Due Date</th>
                    <th>Loan Amount</th>
                    <th>Amount Disbursed</th>
                    <th>Installment Amount</th>
                    <th>Loan status</th>
                    <th>Notes</th>
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
                    <td>Php {{ number_format($loanApplication->financed_amount, 2) }}</td>
                    <td>Php {{ number_format($loanApplication->finance_charge, 2) }}</td>
                    <td>{{ $loanApplication->monthly_pay }}/mo</td>
                    <td class="fw-medium @switch($loanApplication->application_status)
                        @case('pending')
                            text-primary-emphasis
                            @break
                        @case('approved')
                            text-success
                            @break
                        @case('rejected')
                            text-danger
                            @break
                    @endswitch">{{ ucfirst($loanApplication->application_status) }}</td>
                    @if($loanApplication->note)
                      <td><button class='btn btn-primary btn-sm w-100' data-bs-toggle='modal' data-bs-target='#noteModal' data-note="{{ $loanApplication->note }}">Note</button></td>
                      @else
                        <td></td>
                      @endif
                    </tr>
                @endforeach
                </tbody>
              </table>
            </div>
        </div>
      </div>

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
@if(session()->has('success'))
  @push('scripts')
  <script>
      toastr.options = {
              "closeButton": true,
              "progressBar": true,
              "positionClass": "toast-top-right",
              "showDuration": "300",
              "hideDuration": "1000",
              "timeOut": "5000",
              "extendedTimeOut": "1000",
              "showEasing": "swing",
              "hideEasing": "linear",
              "showMethod": "fadeIn",
              "hideMethod": "fadeOut"
          }
          toastr.success("{{ session('success') }}");
  </script>
  @endpush
@endif
@endsection
