<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Welcome to Readify</title>
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
        <div class="header">Welcome to Grizi7!</div>
        <div class="content">
            <p>Hi {{ $user->name }},</p>
            <p>We’re thrilled to have you on board. Your email has been successfully verified, and you're now part of the Grizi7 family.</p>
            <p>Start exploring our platform today!</p>
            <a href="{{ config('app.url') }}" class="button">Get Started</a>
        </div>
        <div class="footer">Happy Reading!<br> The Grizi7 Team</div>
    </div>
</body>

</html>
