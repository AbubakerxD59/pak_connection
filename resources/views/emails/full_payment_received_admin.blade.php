<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Internal Notification – Full Payment Received</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #ffffff; margin: 0; padding: 0;">
    <div style="max-width: 700px; margin: 0 auto; padding: 30px; border: 1px solid #e0e0e0;">

        {{-- Top Logo --}}
        <div style="text-align: center; margin-bottom: 30px;">
            <img src="{{ asset('images/email/logo-top.png') }}" alt="Pak Connections" style="max-width: 180px;">
        </div>

        {{-- Email Content --}}
        {{-- <p style="font-size: 16px;"><strong>Subject:</strong> Full Payment Received – Your Bookings Are Now Being Confirmed</p> --}}

        <p style="font-size: 16px;">Dear POCC,</p>

        <p style="font-size: 16px;">
            Please begin confirming the bookings for the member,
            <strong>{{ $bookedService->user?->full_name ?? 'Unknown Member' }}</strong>, as quickly and accurately as possible.
        </p>

        <p style="font-size: 16px;">Kind regards,</p>

        <p style="font-size: 16px;">
            Member Support Team (MST)<br>
            <strong>Pak Connections</strong>
        </p>

        {{-- Bottom-right Logo --}}
        <div style="text-align: right; margin-top: 40px;">
            <img src="{{ asset('images/email/logo-bottom.png') }}" alt="Pak Connections Logo" style="max-width: 120px;">
        </div>
    </div>
</body>
</html>
