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

        p {
            margin-bottom: 15px;
        }

        ul {
            margin: 0 0 15px 20px;
        }

        .button {
            display: inline-block;
            padding: 12px 20px;
            background-color: #1a73e8;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 0;
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            line-height: 1.5;
        }

        .footer p {
            margin: 5px 0;
        }

        hr {
            margin: 30px 0;
        }

        .link {
            color: red;
        }
    </style>
</head>

<body>

    <div style="text-align: center; margin-bottom: 20px;">
        <img src="{{ asset('assets/img/site_logo.jpg') }}" alt="Pak Connections" style="max-width: 200px;">
    </div>


    {{-- <p><strong>Subject:</strong> Final Invoice – Complete Your Payment to Confirm Your Bookings</p> --}}

    <p>Dear Member <strong>{{ $bookedService->user?->full_name ?? '' }}</strong>,</p>


    <p>We’re excited to let you know that all your requested services have now been prepared, and your itinerary is
        ready to go!</p>

    <p>
        Please find your <strong>Final Invoice</strong> attached below, reflecting the full cost of your selected services. This invoice
        includes all arrangements discussed with your Concierge Manager, and the <strong>£100 deposit</strong> you’ve
        already paid has been deducted from the total.
    </p>

    <p><strong>Action Required: Complete Your Final Payment</strong></p>

    <p>To fully confirm and activate your bookings, please use the secure payment link below:</p>

    {{-- @if (isset($invoiceLink)) --}}
    <p><strong><a href="{{ $invoiceLink ?? '#' }}" class="link" target="_blank">View Final Invoice</a></strong></p>
    {{-- @endif --}}

    {{-- @if (isset($paymentLink)) --}}
    <p><strong><a href="{{ $paymentLink ?? '#' }}" class="link" target="_blank">Pay Final Balance</a></strong></p>
    {{-- @endif --}}

    <p>Once your payment is received:</p>
    <ul>
        <li>All bookings will be locked in and confirmed</li>
        <li>You will receive an <strong>Itinerary & Schedule</strong> Confirmation with full details and contact information</li>
        <li>Our local teams will be on standby for your arrival in Pakistan</li>
    </ul>

    <p>We’re here to assist you every step of the way.</p>

    <p>Thank you for choosing <strong>Pak Connections</strong>. We look forward to welcoming you soon and ensuring your visit is
        seamless, stress-free, and unforgettable.</p>

    <p>
        Warm regards,<br>
        <strong>Naveed Khan</strong><br>
        <strong>CEO, Pak Connections</strong>
    </p>

    <p><strong>"Let us handle the details, so you can enjoy the experience."</strong></p>

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

    <div style="text-align: right; margin-top: 40px;">
        <img src="{{ asset('assets/img/site_logo.jpg') }}" alt="Pak Connections" style="max-width: 200px;">
    </div>

</body>

</html>
