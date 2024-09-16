<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dddddd;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            text-align: center;
            background-color: #4CAF50;
            color: white;
            padding: 10px 0;
            margin-bottom: 20px;
        }
        .email-body {
            text-align: center;
        }
        .otp {
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
        }
        .email-footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h2>Password Reset Request</h2>
        </div>
        <div class="email-body">
            <p>Dear User,</p>
            <p>We received a request to reset your password. Use the OTP below to reset it:</p>
            <div class="otp">{{ $otp }}</div>
            <p>This OTP is valid for the next 15 minutes.</p>
            <p>If you did not request a password reset, please ignore this email.</p>
        </div>
        <div class="email-footer">
            <p>&copy; Wasfeh</p>
        </div>
    </div>
</body>
</html>
