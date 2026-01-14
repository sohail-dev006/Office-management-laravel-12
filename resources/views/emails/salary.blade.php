<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Salary Slip Notification</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f9;">

<!-- Email Container -->
<div style=" background:#ffffff;">

    <!-- Header with Logo & Address -->
    <div class="d-flex align-items-center justify-content-between px-3" 
        style="background-color:#0d6efd; padding:10px 20px; text-align:center; font-family:Arial, sans-serif;">
        
        <!-- Logo -->
        <div class="col-auto">
            <img src="data:image/jpeg;base64,/9j/4AAQ..." alt="Company Logo"
                style="max-height:60px; margin-bottom:8px;">
        </div>
        
        <!-- Office Info -->
        <div class="col-auto" style="color:#e9f1ff; font-size:13px; line-height:1.4;">
            Office Management System<br>
            2nd Floor, Business Plaza, Johar Town, Lahore, Pakistan
        </div>
    </div>


    <!-- Centered Heading Below Header -->
    <div class="d-flex justify-content-center text-center" style="text-align:center; margin:25px 0 0;">
        <h2 class="text-center" style="color:black; text-align: center; font-weight:600; letter-spacing:0.5px; margin:0;">
            Salary Slip Notification
        </h2>
    </div>

    <!-- Email Body -->
    <div style="padding:30px 25px; font-family:Arial, sans-serif; line-height:1.7; color:#333;">
        
        <p style="margin-top:0;">
            Dear <strong>{{ $salary->employee->first_name }} {{ $salary->employee->last_name }}</strong>,
        </p>

        <!-- Employee Info -->
        <p style="margin-bottom:15px; font-size:14px; color:#555;">
            <strong>Employee ID:</strong> 00{{ $salary->employee->id ?? 'N/A' }}<br>
            <strong>Designation:</strong> {{ $salary->employee->designation ?? 'N/A' }}
        </p>

        <p>
            We hope this email finds you well. Please find attached your salary slip for the month of
            <strong style="color:#0d6efd;">
                {{ \Carbon\Carbon::create($salary->year, $salary->month)->format('F Y') }}
            </strong>.
        </p>

        <!-- Salary Summary -->
        <table style="border-collapse:collapse; width:100%; margin-top:20px; border:1px solid #e0e0e0;">
            <tr style="background-color:#f8f9fa;">
                <th style="text-align:left; padding:12px; font-weight:600;">Gross Salary</th>
                <td style="padding:12px;">
                    {{ config('app.currency_symbol', 'PKR') }}
                    {{ number_format($salary->gross_salary, 2) }}
                </td>
            </tr>
            <tr>
                <th style="text-align:left; padding:12px; font-weight:600;">Deductions</th>
                <td style="padding:12px; color:#dc3545;">
                    {{ config('app.currency_symbol', 'PKR') }}
                    {{ number_format($salary->deduction, 2) }}
                </td>
            </tr>
            <tr style="font-weight:bold; background-color:#f1f5ff;">
                <th style="text-align:left; padding:12px;">Net Salary</th>
                <td style="padding:12px; color:#0d6efd;">
                    {{ config('app.currency_symbol', 'PKR') }}
                    {{ number_format($salary->net_salary, 2) }}
                </td>
            </tr>
        </table>

        <p style="margin-top:25px;">
            If you have any questions or require clarification regarding this salary slip,
            please contact the HR department.
        </p>

        <p style="margin-bottom:0;">
            Best regards,<br>
            <strong>HR Department</strong><br>
            Office Management System
        </p>
    </div>

    <!-- Footer -->
    <div style="background-color:#f8f9fa; color:#6c757d; padding:12px; text-align:center; font-size:12px; font-family:Arial, sans-serif;">
        This is a system-generated email. Please do not reply.<br>
        © {{ date('Y') }} Office Management System. All rights reserved.
    </div>

</div>

</body>
</html>
