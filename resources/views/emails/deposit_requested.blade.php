<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        h2 {
            color: #1a73e8;
            margin-bottom: 10px;
        }
        p {
            margin-bottom: 15px;
        }
        ul, ol {
            margin-bottom: 15px;
            padding-left: 20px;
        }
        a.button {
            display: inline-block;
            padding: 12px 20px;
            background-color: #1a73e8;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 15px 0;
        }
        .section-title {
            font-weight: bold;
            margin-top: 25px;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
        }
    </style>
</head>
<body>
     <div style="text-align: center; margin-bottom: 20px;">
        <img src="{{ asset('assets/img/site_logo.jpg') }}" alt="Pak Connections" style="max-width: 200px;">
    </div>

    {{-- <p><strong>Subject:</strong> Deposit Payment Link – Let’s Start Securing Your Bookings</p> --}}

    <p>Dear Member <strong>{{ $bookedService->user?->full_name ?? '' }}</strong>,</p>


    <p>
        Thank you once again for submitting your Services Request Form and confirming the details with our Concierge Team.
        We’re now ready to begin securing your personalised bookings.
    </p>

    <p>
        To proceed, we kindly ask you to pay a <strong>£100 deposit</strong>, which allows us to begin making your reservations, liaising with our trusted partners, and locking in your requested services.
    </p>

    <p><strong>Deposit Payment Link</strong></p>

    <p>Please use the secure button below to make your deposit payment:</p>

    <p>
        <a href="{{ $paymentLink ?? '' }}" class="button" target="_blank">Pay Deposit Now</a>
    </p>

    <p>Once payment is received, our team will:</p>
    <ul>
        <li>Begin arranging all requested services</li>
        <li>Coordinate directly with our sub-contractors and concierge partners</li>
        <li>Prepare your detailed booking schedule and invoice</li>
    </ul>

    <p class="section-title">What Happens After Payment?</p>
    <ol>
        <li>You’ll receive a summary of your itinerary and bookings.</li>
        <li>A final invoice and payment link will be issued.</li>
        <li>Upon full payment, your entire package will be confirmed and ready for your arrival.</li>
    </ol>

    <p>
        If you have any questions or wish to make changes, we’re just a message away.
    </p>

    <p>
        We’re excited to start working our magic on your journey. Thank you for trusting Pak Connections — your very own personal assistant in Pakistan.
    </p>

    <p>Warm regards,<br>
    <strong>Naveed Khan</strong><br>
    CEO, Pak Connections</p>

    <hr>

    <div class="footer">
        <p><strong>Useful Contacts</strong></p>

        <p><strong>Personal Members Services</strong><br>
        UK Callers: +44 203 375 3337<br>
        International Callers: WhatsApp 0044 203 375 3337<br>
        Email: <a href="mailto:MembersSupport@pakconnections.co.uk">MembersSupport@pakconnections.co.uk</a></p>

        <p><strong>Corporate & Business Members Services</strong><br>
        UK Callers: +44 203 375 3337<br>
        International Callers: WhatsApp 0044 203 375 3337<br>
        Email: <a href="mailto:CorporateSupport@pakconnections.co.uk">CorporateSupport@pakconnections.co.uk</a></p>

        <p><strong>Personal Assistant</strong><br>
        Pakistan: 0092 320 5023407<br>
        WhatsApp: 0092 320 5023407</p>

        <p><strong>Emergency / Crisis / Medical (24/7)</strong><br>
        Pakistan: 0092 320 5023407<br>
        WhatsApp: 0092 320 5023407<br>
        Email: <a href="mailto:EmergencySupport@pakconnections.co.uk">EmergencySupport@pakconnections.co.uk</a></p>
    </div>

</body>
</html>
