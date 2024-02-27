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
        <h1>Members Repositories</h1>
        <div class="row pt-3 card" style="margin-top: 2em;">
          <!-- Table -->
          <div class="row m-0">
            <div class="col-lg-12">
              <div class="row">
                <div class="row mb-3">
                  <div class="col-lg-11">
                    <div class="col-lg-8 col-md-8 col-sm-8 d-flex flex-column flex-lg-row">
                      <a href="{{ route('admin.add-repo') }}" class="d-flex align-items-center me-3 ">
                        <button class="btn btn-primary" id="add-mem"><i class="bi bi-plus-lg text-light"></i> &nbsp;Add member</button>
                      </a>
                      <span class="ps-3 border-start border-md-start-0"></span>
                      <div class="">
                        <form action="{{ route('admin.search-repo') }}" method="GET">
                            <div class="">
                                <input class="form-control" type="search" placeholder="Search" id="search-input" aria-describedby="button-addon1">
                            </div>
                        </form>
                      </div>
                      
                  </div>
                </div>
                
              </div>
              <hr>
                <div class="table table-responsive" id="client-repositories">
                  <table id="repository_table" class="table table-bordered" style="font-size: large;">
                    <thead>
                      <tr>
                      <th class='fw-medium'>#</th>
                      <th class='fw-medium'>Account Number</th>
                      <th class='fw-medium'>Name</th>
                      <th class='fw-medium'>Birth Date</th>
                      <th class='fw-medium'>Nature of Work</th>
                      <th class='fw-medium'>Status</th>
                      <th class='fw-medium'>Amount of share</th>
                      <th class='fw-medium'>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($clients as $client)
                        <tr>
                          <td> {{ $loop->iteration }}</td>
                          <td> {{ $client->user->account_number }}</td>
                          <td> {{ $client->user->name }}</td>
                          <td> {{ $client->birth_date->format('F j, Y') }}</td>
                          <td> {{ $client->nature_of_work }}</td>
                          @if ($client->account_status == 'Active')
                            <td class="text-success fw-semibold"> {{ $client->account_status }}</td>
                          @else
                            <td class="text-danger fw-semibold"> {{ $client->account_status }}</td>
                          @endif
                          <td> {{ $client->amount_of_share }}</td>
                          <td>
                            <a href="{{ route('admin.edit-repo', $client->id) }}"><button class="btn btn-primary">Edit</button></a>
                            {{-- <form action="{{ route('admin.delete-repo', $client->id) }}" method="POST" style="display: inline;">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger">Delete</button>
                            </form> --}}
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>  
                </div>
                <div id="pagination" class="pagination">
                </div>
            </div>
        </div>
          <!-- /Table -->

          <!-- Toaster -->

          <!-- /Toaster -->
      </div>
    </div>
  </div>
</div>
@section('script')
<script>
$(document).ready(function() {
  $('#search-input').on('input', function() {
    var query = $(this).val();

    $.ajax({
      url: "{{ route('admin.search-repo') }}",
      type: "GET",
      data: {'search': query},
      success: function(data) {
        // Assuming the table body has an ID of 'repository-table-body'
        $('#repository_table tbody').html(data);
      }
    });
  });
});
</script>
@endsection
@endsection