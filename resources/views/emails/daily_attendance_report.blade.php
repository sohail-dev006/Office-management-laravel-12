<!DOCTYPE html>
<html>
<head>
    <title>Daily Attendance Report</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #2c3e50; color: #fff; }
        td.status { font-weight: bold; color: #fff; text-align: center; border-radius: 4px; }
        td.status-present { background-color: #2ecc71; }
        td.status-late { background-color: #f1c40f; color: #333; }
        td.status-absent { background-color: #e74c3c; }
        td.status-leave { background-color: #9b59b6; }
    </style>
</head>
<body>
    <h2>Daily Attendance Report for {{ $date }}</h2>
    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Working Hours</th>
                <th>Status</th>
                <th>Leave Reason</th>
                <th>Absent Reason</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $att)
                <tr>
                    <td>{{ $att['employee_name'] }}</td>
                    <td>{{ $att['check_in'] ?? '-' }}</td>
                    <td>{{ $att['check_out'] ?? '-' }}</td>
                    <td>{{ $att['working_hours'] ?? '-' }}</td>
                    <td class="status status-{{ $att['status'] }}">{{ ucfirst($att['status']) }}</td>
                    <td>{{ $att['leave_reason'] ?? '-' }}</td>
                    <td>{{ $att['absent_reason'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
