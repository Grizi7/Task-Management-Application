<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Email Confirmation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background-color: #f4f4f7;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #1a82e2;
            color: #ffffff;
            text-align: center;
            padding: 20px;
            font-size: 22px;
            font-weight: bold;
        }

        .content {
            padding: 30px;
            text-align: center;
            font-size: 16px;
            color: #333;
        }

        .verification-code {
            font-size: 36px;
            font-weight: bold;
            color: #1a82e2;
            margin: 20px 0;
        }

        .button {
            display: inline-block;
            background-color: #1a82e2;
            color: #ffffff;
            padding: 14px 28px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            padding: 20px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">Email Verification</div>
        <div class="content">
            <p>Your verification code is:</p>
            <div class="verification-code">{{ $user->email_verification_token }}</div>
            <p>This code expires in 2 hours.</p>
            <a href="https://grizi7.space/verify-email?token={{ $user->id }}|{{ $user->email_verification_token }}" class="button">Verify My Email</a>
        </div>
        <div class="footer">Cheers,<br> Grizi7</div>
    </div>
</body>

</html>
