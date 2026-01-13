<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Attendance Summary</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f7; font-family:Arial, sans-serif; color:#333;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:10px; box-shadow:0 2px 5px rgba(0,0,0,0.1); padding:20px;">
                    <tr>
                        <td align="center" style="padding-bottom:20px;">
                            <h2 style="margin:0; color:#2c3e50;">Attendance Summary</h2>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table width="100%" cellpadding="5" cellspacing="0">
                                <tr>
                                    <td style="font-weight:bold; color:#555; width:40%;">Date:</td>
                                    <td style="text-align:right; color:#2c3e50;">{{ $attendance->date }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold; color:#555;">Check In:</td>
                                    <td style="text-align:right; color:#2c3e50;">{{ $attendance->check_in ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold; color:#555;">Check Out:</td>
                                    <td style="text-align:right; color:#2c3e50;">{{ $attendance->check_out ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold; color:#555;">Total Hours:</td>
                                    <td style="text-align:right; color:#2c3e50;">{{ $attendance->working_hours ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold; color:#555;">Status:</td>
                                    <td style="text-align:right;">
                                        <span style="display:inline-block; padding:5px 12px; border-radius:20px; font-weight:bold; color:#fff;
                                            background-color:
                                                {{ $attendance->status === 'present' ? '#2ecc71' : ($attendance->status === 'late' ? '#f1c40f' : ($attendance->status === 'absent' ? '#e74c3c' : '#9b59b6')) }};
                                        ">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top:20px; font-size:12px; color:#999; text-align:center;">
                            &copy; {{ date('Y') }} Your Company. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
