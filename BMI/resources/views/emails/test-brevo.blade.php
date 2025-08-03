<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Brevo API Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #10b981;
        }
        .success {
            background: #dcfce7;
            color: #166534;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
            border: 1px solid #bbf7d0;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .info-table th,
        .info-table td {
            padding: 8px 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-table th {
            background: #f3f4f6;
            font-weight: 600;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Brevo API Connection Test</h1>
        <p>BMI Malnutrition Monitoring System</p>
    </div>

    <div class="content">
        <div class="success">
            <strong>✅ Success!</strong> Your Brevo API connection is working properly.
        </div>

        <p>This email confirms that your BMI Malnutrition Monitoring System can successfully send emails through Brevo's SMTP service.</p>

        <h3>Connection Details:</h3>
        <table class="info-table">
            <tr>
                <th>Timestamp</th>
                <td>{{ $testData['timestamp'] }}</td>
            </tr>
            <tr>
                <th>Application</th>
                <td>{{ $testData['app_name'] }}</td>
            </tr>
            <tr>
                <th>Environment</th>
                <td>{{ $testData['environment'] }}</td>
            </tr>
            <tr>
                <th>SMTP Host</th>
                <td>{{ $testData['brevo_host'] }}</td>
            </tr>
            <tr>
                <th>From Address</th>
                <td>{{ $testData['from_address'] }}</td>
            </tr>
        </table>

        <h3>What's Next?</h3>
        <ul>
            <li>Your email configuration is working correctly</li>
            <li>Registration emails will be sent successfully</li>
            <li>Email verification for new users is enabled</li>
            <li>System notifications will be delivered properly</li>
        </ul>

        <p><strong>Note:</strong> This is an automated test email. You can safely delete it.</p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} BMI Malnutrition Monitoring System</p>
        <p>Powered by Laravel & Brevo</p>
    </div>
</body>
</html>
