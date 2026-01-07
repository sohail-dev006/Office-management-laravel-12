<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Salary Slip</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .container {
            width: 100%;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            color: #2c3e50;
        }

        .company {
            font-size: 14px;
            font-weight: bold;
        }

        .row {
            width: 100%;
            margin-bottom: 10px;
        }

        .label {
            width: 40%;
            display: inline-block;
            font-weight: bold;
        }

        .value {
            width: 58%;
            display: inline-block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th {
            background: #2c3e50;
            color: #fff;
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .total {
            font-weight: bold;
            background: #ecf0f1;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">

    <!-- HEADER -->
    <div class="header">
        <div class="company">Office Management System</div>
        <h2>Salary Slip</h2>
        <p>
            {{ \Carbon\Carbon::create($salary->year, $salary->month, 1)->format('F Y') }}
        </p>
    </div>

    <!-- EMPLOYEE INFO -->
    <div class="row">
        <span class="label">Employee Name:</span>
        <span class="value">
            {{ $salary->employee->first_name }} {{ $salary->employee->last_name }}
        </span>
    </div>

    <div class="row">
        <span class="label">Employee ID:</span>
        <span class="value">{{ $salary->employee->id }}</span>
    </div>

    <div class="row">
        <span class="label">Designation:</span>
        <span class="value">{{ $salary->employee->designation ?? 'N/A' }}</span>
    </div>

    <!-- SALARY DETAILS -->
    <table>
        <tr>
            <th>Description</th>
            <th>Amount</th>
        </tr>

        <tr>
            <td>Gross Salary</td>
            <td>{{ number_format($salary->gross_salary, 2) }}</td>
        </tr>

        <tr>
            <td>Deduction</td>
            <td>{{ number_format($salary->deduction, 2) }}</td>
        </tr>

        <tr class="total">
            <td>Net Salary</td>
            <td>{{ number_format($salary->net_salary, 2) }}</td>
        </tr>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        This is a computer generated salary slip.  
        No signature is required.
    </div>

</div>

</body>
</html>
