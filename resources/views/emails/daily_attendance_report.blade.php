<!DOCTYPE html>
<html>
<head>
    <title>Daily Attendance Report</title>
</head>
<body>
    <h2>Attendance Report for {{ $date }}</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Working Hours</th>
                <th>Status</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $att)
                <tr>
                    <td>{{ $att->employee->first_name }} {{ $att->employee->last_name }}</td>
                    <td>{{ $att->check_in ?? '-' }}</td>
                    <td>{{ $att->check_out ?? '-' }}</td>
                    <td>{{ $att->working_hours ?? '-' }}</td>
                    <td>{{ ucfirst($att->status) }}</td>
                    <td>
                        @if($att->status === 'absent')
                            {{ $att->absent_reason ?? 'N/A' }}
                        @elseif($att->status === 'leave')
                            {{ $att->leave?->reason ?? 'N/A' }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
