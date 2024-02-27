{{-- Ensure this view only generates <tr> elements --}}
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