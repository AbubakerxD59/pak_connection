<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Full Payment Received</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #ffffff; margin: 0; padding: 0;">
    <div style="max-width: 700px; margin: 0 auto; padding: 30px; border: 1px solid #e0e0e0;">

        @include('emails.partials.header')

        <!-- Email Content -->
        <p style="font-size: 16px;">Dear POCC,</p>
        <p style="font-size: 16px;">Payment has been received.</p>
        <p style="font-size: 16px;">
            Please begin confirming the bookings for
            <strong>{{ ucfirst($bookedService->user?->full_name) . '(' . $bookedService->user->membership_id . ')' ?? 'the Member' }},</strong>
            as quickly and accurately as
            possible.
        </p>

        <p style="font-size: 16px;">Kind regards,</p>

        <p style="font-size: 16px;">
            Member Support Team (MST)<br>
            <strong>Pak Connections</strong>
        </p>

        <!-- Bottom-right Logo -->
        <div style="text-align: right; margin-top: 40px;">
            <img src="https://adminpakconnection.netforcedemo.com/assets/img/site_logo.jpg" alt="Pak Connections Logo"
                style="max-width: 120px;">
        </div>
    </div>
</body>

</html>
