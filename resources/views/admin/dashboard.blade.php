@extends('layouts.app')
@section('title', 'Dashboard')
@section('css')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection
@section('content')

<div id="page-content-wrapper">
    <div class="container-fluid xyz">
      <div class="row">
        <div class="col-lg-12">
          <h1 id="user-greet">
            Hi, <span> {{ Auth::user()->name }} </span>
          </h1>
          <h1>
            Dashboard Overview
          </h1>
          <div class="row gap-2" style="margin-top: 2em;">
            <div class=".col-md-12">
              <div class="row">
                <!-- Requests -->
                <div class="col-md-2 w-50">
                  <div id="card-container" style="width: auto;">
                    <div id="card-title">
                        <table>
                          <thead id="">
                              <tr>
                                  <td>Pending loan request</td>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                  <th style="font-size: 25px;">{{ $pendingLoanRequests }} {{ $pendingLoanRequests > 1 ? 'Applications' : 'Application' }}</th>
                              </tr>
                          </tbody>                        
                      </table>
                    </div>
                  </div>
                </div>
                {{-- Pending Application for the last 24 hours --}}
                <div class="col-md-2 h-25 w-50">
                  <div id="card-container" style="width: auto;">
                    <div id="card-title">
                        <table>
                          <thead id="">
                              <tr>
                                  <td>New loan request for the past 24 hours</td>
                              </tr>
                          </thead>
                          <tbody>
                              <tr>
                                  <th style="font-size: 25px;">{{ $newLoanRequestsLast24Hours }} {{ $newLoanRequestsLast24Hours > 1 ? 'Applications' : 'Application' }}</th>
                              </tr>
                          </tbody>                        
                      </table>
                    </div>
                  </div>
                </div>
                
              </div>
              
              <div class="row mt-3">
                {{-- Total of paid --}}
                <div class="col-md-2 h-25 w-50">
                  <div id="card-container">
                    <div id="card-title">
                      <table>
                          <thead>
                              <tr>
                                  <td>Total of Paid Members</td>
                                </tr>
                          </thead>
                        <tbody>
                          <tr>
                              <th style="font-size: 25px;">{{ $totalClients }} {{ $totalClients > 1 ? 'Members' : 'Member' }}</th>
                            </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                {{-- Total of Unpaid --}}
                <div class="col-md-2 h-25 w-50">
                  <div id="card-container">
                    <div id="card-title">
                      <table>
                          <thead>
                              <tr>
                                  <td>Total of Unpaid Members</td>
                                </tr>
                          </thead>
                        <tbody>
                          <tr>
                              <th style="font-size: 25px;">{{ $totalClients }} {{ $totalClients > 1 ? 'Members' : 'Member' }}</th>
                            </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12 mt-3">
                  <!-- Count -->
                    <div class="col-md-2 h-25 w-100">
                      <div id="card-container">
                        <div id="card-title">
                          <table>
                              <thead>
                                  <tr>
                                      <td>Total of Members</td>
                                    </tr>
                              </thead>
                            <tbody>
                              <tr>
                                  <th style="font-size: 25px;">{{ $totalClients }} {{ $totalClients > 1 ? 'Members' : 'Member' }}</th>
                                </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
              
              
            </div>
              
          </div>
      </div>
    </div>
  </div>
</div>

@endsection