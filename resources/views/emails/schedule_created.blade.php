<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Your Confirmed Travel Itinerary and Schedule</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #ffffff; margin: 0; padding: 0;">
    <div style="max-width: 700px; margin: 0 auto; padding: 30px; border: 1px solid #e0e0e0;">

        @include('emails.partials.header')

        {{-- Email Content --}}
        <p style="font-size: 16px;">
        <p style="font-size: 16px;">Dear <strong>{{ $bookedService->user?->full_name ?? 'Member' }}</strong>,</p>
        </p>

        <p style="font-size: 16px;">
            We’re excited to share with you your <strong>Personal Itinerary Diary Schedule</strong>, thoughtfully
            prepared and confirmed by our team to ensure a smooth, stress-free, and memorable journey through Pakistan.
        </p>

        <p style="font-size: 16px;">
            Your custom itinerary outlines all your bookings and scheduled services, including dates, times, contact
            points, and relevant instructions. It’s designed to keep you informed, organised, and supported every step
            of the way.
        </p>


        @if ($bookedService->schedule_created)
            {{-- <a href="{{ url(asset('uploads/').'/'.$bookedService->schedule_pdf) }}" target="_blank" --}}
            <a href="{{ url(asset('/') . $bookedService->schedule_pdf) }}" target="_blank"
                style="display: inline-block; padding: 12px 20px; background-color: #1a73e8; color: white; text-decoration: none; border-radius: 5px; margin: 15px 0; font-weight: bold;">
                Click here to see Itinerary & Schedule (PDF).
            </a>
        @endif

        <p style="font-size: 16px;">
            Please take a moment to review your schedule carefully. If you require any changes, updates, or additional
            services, our Member Services Team is available to assist you.
        </p>

        <p style="font-size: 16px;">
            We look forward to delivering an outstanding experience and are here to assist you every step of the way.
        </p>

        <p style="font-size: 16px;">
            Wishing you safe travels and a wonderful stay.
        </p>

        <p style="font-size: 16px;">
            Warm regards,<br>
            Members Support Team<br>
            <strong>Pak Connections</strong><br>
            <em>“Let us handle the details, so you can enjoy the experience.”</em>
        </p>

        {{-- Bottom-right Logo --}}
        <div style="text-align: right; margin-top: 40px;">
            <img src="https://adminpakconnection.netforcedemo.com/assets/img/site_logo.jpg" alt="Pak Connections Logo"
                style="max-width: 120px;">
        </div>
    </div>
</body>

</html>
