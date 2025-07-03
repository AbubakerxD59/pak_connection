<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to Pakistan – Your Itinerary & Support Details Reminder</title>
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
        {{-- <p class="section"><strong>Subject:</strong> Welcome to Pakistan – Your Itinerary & Support Details Reminder</p> --}}

        <p class="section">Dear Member{{ isset($bookedService->user) ? ' ' . $bookedService->user->full_name : '' }},</p>

        <p class="section">
            A very warm welcome on your safe arrival into Pakistan! We’re delighted to have you here and look forward
            to making your visit smooth, enjoyable, and truly memorable.
        </p>

        <p class="section">
            Your <strong>Personal Itinerary & Schedule</strong> has been carefully prepared and is attached for your convenience,
            outlining all your bookings, service timings, and contact points.
        </p>

        <p class="section important">STAFF: Please attach the Itinerary & Schedule (PDF) here.</p>

        <p class="section">
            Should you need any changes, additional services, or assistance during your stay, our dedicated
            Member Services Team is available around the clock.
        </p>

        <p class="section">
            We're here to support you every step of the way.
        </p>

        <p class="section">
            Wishing you a wonderful stay in Pakistan.
        </p>

        <p class="section">
            Warm regards,<br>
            Members Support Team<br>
            <strong>Pak Connections</strong><br>
            <em>“Your Personal Assistant in Pakistan”</em>
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
