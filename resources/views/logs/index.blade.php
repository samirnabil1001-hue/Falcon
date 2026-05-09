
<table class="w-full border">
    <thead>
        <tr>
            <th>User</th>
            <th>Method</th>
            <th>URL</th>
            <th>IP</th>
            <th>Date</th>
        </tr>
    </thead>

    <tbody>
        @foreach($logs as $log)
            <tr>
                <td>{{ $log->user_id }}</td>
                <td>{{ $log->method }}</td>
                <td>{{ $log->url }}</td>
                <td>{{ $log->ip_address }}</td>
                <td>{{ $log->created_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $logs->links() }}