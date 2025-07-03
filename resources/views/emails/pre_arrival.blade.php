<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reminder: Your Upcoming Travel & Itinerary Schedule</title>
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

        .logo-bottom {
            text-align: right;
            margin-top: 40px;
        }

        .logo-bottom img {
            max-width: 120px;
        }

        .section {
            font-size: 16px;
            margin-bottom: 15px;
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
    </style>
</head>
<body>
    <div class="container">

        {{-- Top Logo --}}
        <div class="logo-top">
            <img src="{{ asset('images/email/logo-top.png') }}" alt="Pak Connections">
        </div>

        {{-- Email Content --}}
        {{-- <p class="section"><strong>Subject:</strong> Reminder: Your Upcoming Travel & Itinerary Schedule</p> --}}

        <p class="section">Dear Member{{ isset($bookedService->user) ? ' ' . $bookedService->user->full_name : '' }},</p>

        <p class="section">
            This is a friendly reminder that your journey to Pakistan is just days away.
            Your <strong>Personal Itinerary Diary Schedule</strong> has been carefully prepared and confirmed
            to ensure a smooth, stress-free visit.
        </p>

        <p class="section important">STAFF: Please re-attach the Itinerary & Schedule (PDF) here.</p>

        <p class="section">
            Kindly take a moment to review your schedule. For any updates, changes, or additional service requests,
            our Member Services Team is ready to assist you.
        </p>

        <p class="section">
            We look forward to welcoming you and are here to assist every step of the way.
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
