<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Salary Slip Notification</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f9;">

<!-- Email Container -->
<div style="max-width:650px; margin:0 auto; background:#ffffff;">

    <!-- Header with Logo & Address -->
    <div style="background-color:#0d6efd; padding:25px 20px; text-align:center; font-family:Arial, sans-serif;">
        <img src="data:image/jpeg;base64,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" alt="Company Logo"
             style="max-height:60px; margin-bottom:8px;">

        <div style="color:#e9f1ff; font-size:13px; line-height:1.4;">
            Office Management System<br>
            2nd Floor, Business Plaza, Johar Town, Lahore, Pakistan
        </div>

        <h2 style="color:#ffffff; margin:12px 0 0; font-weight:600; letter-spacing:0.5px;">
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
