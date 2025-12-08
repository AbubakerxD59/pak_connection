<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Welcome to Pakistan – Your Booking Reminder</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #ffffff; margin: 0; padding: 0;">
    <div style="max-width: 700px; margin: 0 auto; padding: 30px; border: 1px solid #e0e0e0;">

        @include('emails.partials.header')

        {{-- Email Content --}}
        <p style="font-size: 16px;">
        <p style="font-size: 16px;">Dear <strong>{{ $bookedService->user?->full_name ?? 'Member' }}{{ !empty($bookedService->user?->membership_id) ? ' (' . $bookedService->user->membership_id . ')' : '' }}</strong>,</p>
        </p>

        <p style="font-size: 16px;">
            A very warm welcome on your safe arrival into Pakistan! We’re delighted to have you here and look forward
            to making your visit smooth, enjoyable, and truly memorable.
        </p>

        <p style="font-size: 16px;">
            Should you need any additional services, or assistance during your stay, our dedicated Member Services Team
            is available around the clock.
        </p>

        <p style="font-size: 16px;">
            We're here to support you every step of the way.
        </p>

        <p style="font-size: 16px;">
            Wishing you a wonderful stay in Pakistan.
        </p>

        <p style="font-size: 16px;">
            Warm regards,<br>
            Members Support Team<br>
            <strong>Pak Connections</strong><br>
            <em>“Your Personal Assistant in Pakistan”</em>
        </p>

        <hr style="margin: 30px 0;">


        @include('emails.partials.footer')

    </div>
</body>

</html>
