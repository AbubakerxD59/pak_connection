<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>We Hope You Enjoyed Your Journey!</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #ffffff; margin: 0; padding: 0;">
    <div style="max-width: 700px; margin: 0 auto; padding: 30px; border: 1px solid #e0e0e0;">

        @include('emails.partials.header')

        {{-- Email Content --}}
        <p style="font-size: 16px;">
        <p style="font-size: 16px;">Dear <strong>{{ $bookedService->user?->full_name ?? 'Member' }}{{ !empty($bookedService->user?->membership_id) ? ' (' . $bookedService->user->membership_id . ')' : '' }}</strong>,</p>
        </p>

        <p style="font-size: 16px;">
            Thank you for choosing <strong>Pak Connections</strong> to support your recent visit to Pakistan. We hope
            your journey was smooth, memorable, and truly enjoyable.
        </p>

        <p style="font-size: 16px;">
            It has been our pleasure to assist you. Your bookings ll will remain on file should you need to refer back
            to any bookings or details.
        </p>

        <p style="font-size: 16px;">
            If you have any feedback or suggestions to help us improve our services, or would like to book future
            services, our <strong>Member Services Team</strong> is always here to help.
        </p>

        <p style="font-size: 16px;">
            We look forward to welcoming you again in the future.
        </p>

        <p style="font-size: 16px; font-weight: bold;">Warm regards,</p>
        <p style="font-size: 16px;">
            <strong>
                Member Support Team<br>Pak Connections
            </strong>
            <br>
            <em>“Your Personal Assistant in Pakistan”</em>
        </p>

        <hr style="margin: 30px 0;">

        @include('emails.partials.footer')


    </div>
</body>

</html>
