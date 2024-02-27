@foreach ($clients as $client)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $client->user->account_number }}</td>
    <td>{{ $client->user->name }}</td>
    <td>{{ $client->birth_date->format('F j, Y') }}</td>
    <td>{{ $client->nature_of_work }}</td>
    <td class="{{ $client->account_status == 'Active' ? 'text-success' : 'text-danger' }} fw-semibold">
        {{ $client->account_status }}
    </td>
    <td>{{ $client->amount_of_share }}</td>
    <td>
        <a href="{{ route('admin.edit-repo', $client->id) }}"><button class="btn btn-primary">Edit</button></a>
        <form action="{{ route('admin.delete-repo', $client->id) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </td>
</tr>
@endforeach