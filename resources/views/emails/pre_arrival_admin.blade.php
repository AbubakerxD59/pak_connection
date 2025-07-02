<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Member Pre Arrival Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
            padding: 30px;
            border: 1px solid #e0e0e0;
        }

        .logo-top {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-top img {
            max-width: 180px;
        }

        .section {
            font-size: 16px;
            margin-bottom: 18px;
        }

        .footer {
            font-size: 15px;
            margin-top: 30px;
        }

        .logo-bottom {
            text-align: right;
            margin-top: 40px;
        }

        .logo-bottom img {
            max-width: 120px;
        }
    </style>
</head>
<body>
    <div class="container">

        {{-- Top Logo --}}
        <div class="logo-top">
            <img src="{{ asset('images/email/logo-top.png') }}" alt="Pak Connections">
        </div>

        {{-- Email Content --}}
        {{-- <p class="section"><strong>Subject:</strong> Member Pre Arrival Confirmation</p> --}}

        <p class="section">
            Dear POCC,
        </p>

        <p class="section">
            Please begin the <strong>Pre-Arrival process</strong> and reconfirm the bookings for the Member,
            as quickly and accurately as possible. Their arrival is in <strong>7 days</strong>.
        </p>

        <p class="section">
            Kind regards,<br>
            <strong>Member Support Team (MST)</strong><br>
            Pak Connections
        </p>

        {{-- Bottom Logo --}}
        <div class="logo-bottom">
            <img src="{{ asset('images/email/logo-bottom.png') }}" alt="Pak Connections Logo">
        </div>
    </div>
</body>
</html>
