<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Confirmed Travel Itinerary and Schedule</title>
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

        .important {
            font-weight: bold;
            color: #cc0000;
        }

        .footer {
            font-size: 15px;
            margin-top: 30px;
        }

        .footer p {
            margin: 8px 0 16px 0;
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
        {{-- <p class="section"><strong>Subject:</strong> Your Confirmed Travel Itinerary and Schedule</p> --}}

        <p class="section">Dear Member{{ isset($bookedService->user) ? ' ' . $bookedService->user->full_name : '' }},</p>

        <p class="section">
            We’re excited to share with you your <strong>Personal Itinerary Diary Schedule</strong>, thoughtfully prepared and confirmed by our team to ensure a smooth, stress-free, and memorable journey through Pakistan.
        </p>

        <p class="section">
            Your custom itinerary outlines all your bookings and scheduled services, including dates, times, contact points, and relevant instructions. It’s designed to keep you informed, organised, and supported every step of the way.
        </p>

        <p class="section important">IMPORTANT:<br>STAFF: Attach the Itinerary & Schedule (PDF) here.</p>

        <p class="section">
            Please take a moment to review your schedule carefully. If you require any changes, updates, or additional services, our Member Services Team is available to assist you.
        </p>

        <p class="section">
            We look forward to delivering an outstanding experience and are here to assist you every step of the way.
        </p>

        <p class="section">
            Wishing you safe travels and a wonderful stay.
        </p>

        <p class="section">
            Warm regards,<br>
            Members Support Team<br>
            <strong>Pak Connections</strong><br>
            <em>“Let us handle the details, so you can enjoy the experience.”</em>
        </p>

        {{-- Useful Contacts --}}
        <div class="footer">
            <p><strong>Useful Contacts</strong></p>

            <p>
                <strong>Personal Members Services</strong><br>
                UK Callers: +44 203 375 3337<br>
                International Callers: WhatsApp 0044 203 375 3337<br>
                Email: <a href="mailto:MembersSupport@pakconnections.co.uk">MembersSupport@pakconnections.co.uk</a>
            </p>

            <p>
                <strong>Corporate & Business Members Services</strong><br>
                UK Callers: +44 203 375 3337<br>
                International Callers: WhatsApp 0044 203 375 3337<br>
                Email: <a href="mailto:CorporateSupport@pakconnections.co.uk">CorporateSupport@pakconnections.co.uk</a>
            </p>

            <p>
                <strong>Personal Assistant</strong><br>
                Pakistan: 0092 320 5023407<br>
                WhatsApp: 0092 320 5023407
            </p>

            <p>
                <strong>Emergency / Crisis / Medical (24/7)</strong><br>
                Pakistan: 0092 320 5023407<br>
                WhatsApp: 0092 320 5023407<br>
                Email: <a href="mailto:EmergencySupport@pakconnections.co.uk">EmergencySupport@pakconnections.co.uk</a>
            </p>
        </div>

        {{-- Bottom-right Logo --}}
        <div class="logo-bottom">
            <img src="{{ asset('images/email/logo-bottom.png') }}" alt="Pak Connections Logo">
        </div>
    </div>
</body>
</html>
