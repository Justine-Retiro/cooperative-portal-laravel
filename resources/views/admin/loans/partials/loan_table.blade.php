<tbody>
@foreach ($loanApplications as $loanApplication)
<tr>
    <td> {{ $loop->iteration }} </td>
    <td><a href="{{ route('admin.loan.application', $loanApplication->loan_reference) }}" class="text-decoration-none text-primary">{{ $loanApplication->loan_reference }}</a></td>
    <td> {{  $loanApplication->user->account_number}} </td>
    <td> {{ $loanApplication->customer_name }} </td>
    <td> {{ $loanApplication->college }} </td>
    <td> {{ $loanApplication->loan_type }} </td>
    <td> {{ \Carbon\Carbon::parse($loanApplication->application_date)->format('m/d/Y') }} </td>
    <td> Php {{ number_format($loanApplication->financed_amount, 2) }} </td>
    <td> Php {{ number_format($loanApplication->finance_charge, 2) }}</td>
    <td class="@if($loanApplication->application_status == 'pending') text-primary-emphasis fw-medium @elseif($loanApplication->application_status == 'approved') text-success fw-medium @elseif($loanApplication->application_status == 'rejected') text-danger fw-medium @endif"> {{ ucfirst($loanApplication->application_status) }} </td>
  </tr>
@endforeach
</tbody>
