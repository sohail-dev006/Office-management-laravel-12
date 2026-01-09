<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Salary Slip</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #2c3e50;
            background: #fff;
        }

        .container {
            width: 100%;
            padding: 25px;
            border: 1px solid #dcdcdc;
        }

        /* Header */
        .header {
            text-align: center;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 12px;
            margin-bottom: 25px;
        }

        .company {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .month {
            font-size: 13px;
            color: #555;
            margin-top: 4px;
        }

        /* Section Title */
        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin: 20px 0 10px;
            color: #0d6efd;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
        }

        /* Employee Info */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 6px 4px;
            vertical-align: top;
        }

        .label {
            width: 25%;
            font-weight: bold;
            color: #555;
        }

        .value {
            width: 25%;
        }

        /* Salary Table */
        table.salary {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table.salary th {
            background: #0d6efd;
            color: #fff;
            padding: 9px;
            border: 1px solid #ddd;
            font-size: 12px;
            text-align: left;
        }

        table.salary td {
            padding: 9px;
            border: 1px solid #ddd;
        }

        table.salary tr:nth-child(even) {
            background: #f8f9fa;
        }

        .total {
            font-weight: bold;
            background: #eef3ff;
        }

        /* Footer */
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
        <div class="month">
            Salary Slip — {{ \Carbon\Carbon::create($salary->year, $salary->month)->format('F Y') }}
        </div>
    </div>

    <!-- EMPLOYEE INFO -->
    <div class="section-title">Employee Information</div>

    <table class="info-table">
        <tr>
            <td class="label">Employee Name</td>
            <td class="value">
                {{ $salary->employee->first_name }} {{ $salary->employee->last_name }}
            </td>

            <td class="label">Employee ID</td>
            <td class="value">
                EMP-{{ str_pad($salary->employee->id, 4, '0', STR_PAD_LEFT) }}
            </td>
        </tr>

        <tr>
            <td class="label">Designation</td>
            <td class="value">{{ $salary->employee->designation ?? 'N/A' }}</td>

            <td class="label">Working Days</td>
            <td class="value">{{ $salary->working_days }}</td>
        </tr>
    </table>

    <!-- SALARY DETAILS -->
    <div class="section-title">Salary Details</div>

    <table class="salary">
        <tr>
            <th>Description</th>
            <th>Amount</th>
        </tr>

        <tr>
            <td>Present Days</td>
            <td>{{ $salary->present_days }}</td>
        </tr>

        <tr>
            <td>Leave Days</td>
            <td>{{ $salary->leaves }}</td>
        </tr>

        <tr>
            <td>Absent Days</td>
            <td>{{ $salary->absent_days }}</td>
        </tr>

        <tr>
            <td>Gross Salary</td>
            <td>
                {{ config('app.currency_symbol', 'PKR') }}
                {{ number_format($salary->gross_salary, 2) }}
            </td>
        </tr>

        <tr>
            <td>Deductions</td>
            <td>
                {{ config('app.currency_symbol', 'PKR') }}
                {{ number_format($salary->deduction, 2) }}
            </td>
        </tr>

        <tr class="total">
            <td>Net Salary</td>
            <td>
                {{ config('app.currency_symbol', 'PKR') }}
                {{ number_format($salary->net_salary, 2) }}
            </td>
        </tr>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        This is a system generated salary slip.<br>
        No signature is required.
    </div>

</div>

</body>
</html>
