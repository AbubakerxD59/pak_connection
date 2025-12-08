<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pak Connection Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #2c3e50;
        }

        p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        a {
            color: #6f42c1;
            text-decoration: none;
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #888;
        }

        .button {
            background-color: #6f42c1;
            color: white !important;
            padding: 10px 10px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
            font-weight: bold;
        }

        .button:hover {
            background-color: #5936a8;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Hello {{ $user->full_name ?? 'User' }}{{ !empty($user->membership_id) ? ' (' . $user->membership_id . ')' : '' }} !</h1>

        <p>We received a request to reset your Pak Connection account password. You can reset your password by clicking the
            button below:</p>

        <p>If you didn’t request a password reset, you can ignore this email. Your password won’t change unless you
            access the link and set a new one.</p>

        <p>Visit the Pak Connection application at the following URL:</p>
        <p><a class="button" href="{{ $user->token }}">Reset Your Password</a></p>

      

        <p>Best regards,<br>The Pak Connection Team</p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Pak Connection. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
