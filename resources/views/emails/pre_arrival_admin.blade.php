<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Member Pre Arrival Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #ffffff; margin: 0; padding: 0;">
    <div style="max-width: 700px; margin: 0 auto; padding: 30px; border: 1px solid #e0e0e0;">

        @include('emails.partials.header')

        {{-- Email Content --}}
        <p style="font-size: 16px;">Dear POCC,</p>

        <p style="font-size: 16px;">
            Please begin the <strong>Pre-Arrival process</strong> and reconfirm the bookings for the Member,
            as quickly and accurately as possible. Their arrival is in <strong>7 days</strong>.
        </p>

        <p style="font-size: 16px;">
            Kind regards,<br>
            <strong>Member Support Team (MST)</strong><br>
            Pak Connections
        </p>

        {{-- Bottom Logo --}}
        <div style="text-align: right; margin-top: 40px;">
            <img src="{{ asset('assets/img/site_logo.jpg') }}" alt="Pak Connections" style="max-width: 200px;">
        </div>
    </div>
</body>
</html>
