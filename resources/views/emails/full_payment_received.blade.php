<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Final Payment Confirmation</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #ffffff; margin: 0; padding: 0;">
    <div style="max-width: 700px; margin: 0 auto; padding: 30px; border: 1px solid #e0e0e0;">

        {{-- Top Logo --}}
        <div style="text-align: center; margin-bottom: 30px;">
            <img src="{{ asset('images/email/logo-top.png') }}" alt="Pak Connections" style="max-width: 180px;">
        </div>

        {{-- Email Content --}}
        <p style="font-size: 16px;">Dear Member{{ $name }},</p>

        <p style="font-size: 16px;">We’re pleased to confirm that we have received your final payment in full — thank
            you!</p>

        <p style="font-size: 16px;">
            Your entire booking is now fully confirmed, and our team has activated all services you requested. From this
            point forward,
            your journey is in the hands of our expert concierge teams who are fully briefed and ready to support you
            every step of the way.
        </p>

        {{-- Attach Invoice Info --}}
        <p style="font-size: 16px; font-weight: bold;">{{ $invoice_url }}</p>

        <p style="font-size: 16px;"><strong>What Happens Next?</strong></p>
        <ol style="font-size: 16px; padding-left: 20px;">
            <li>
                You will shortly receive your Itinerary & Schedule Confirmation, which includes:
                <ul>
                    <li>A summary of all confirmed services</li>
                    <li>Contact details for your local concierge</li>
                    <li>Any relevant booking references or schedules</li>
                    <li>24/7 support contact numbers</li>
                </ul>
            </li>
            <li>Our team in Pakistan is now on standby and preparing for your arrival.</li>
            <li>Should you need any further assistance, please don’t hesitate to contact us — we’re always happy to
                assist.</li>
        </ol>

        <p style="font-size: 16px;">
            Thank you for trusting <strong>Pak Connections</strong>. Your journey is now our responsibility, and we look
            forward to delivering
            an unforgettable experience from the moment you land to the moment you leave.
        </p>

        <p style="font-size: 16px; font-weight: bold;">Warm regards,</p>
        <p style="font-size: 16px;">
            Naveed Khan, CEO<br>
            <strong>Pak Connections</strong><br>
            <em>“Your very own Personal Assistant in Pakistan”</em>
        </p>

        <hr style="margin: 30px 0;">

        {{-- Useful Contacts --}}
        <h3 style="font-size: 16px;">Useful Contacts</h3>
        <table style="font-size: 15px;">
            <tr>
                <td><strong>Personal Members Services</strong></td>
                <td style="padding-left: 20px;">
                    UK: +44 203 375 3337<br>
                    WhatsApp: 0044 203 375 3337<br>
                    Email: MembersSupport@pakconnections.co.uk
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td><strong>Corporate & Business Members</strong></td>
                <td style="padding-left: 20px;">
                    UK: +44 203 375 3337<br>
                    WhatsApp: 0044 203 375 3337<br>
                    Email: CorporateSupport@pakconnections.co.uk
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td><strong>Personal Assistant</strong></td>
                <td style="padding-left: 20px;">
                    Pakistan: 0092 320 5023407<br>
                    WhatsApp: 0092 320 5023407
                </td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td><strong>Emergency / Crisis / Medical</strong></td>
                <td style="padding-left: 20px;">
                    Pakistan: 0092 320 5023407<br>
                    WhatsApp: 0092 320 5023407<br>
                    Email: EmergencySupport@pakconnections.co.uk
                </td>
            </tr>
        </table>

        {{-- Bottom-right Logo --}}
        <div style="text-align: right; margin-top: 40px;">
            <img src="{{ asset('images/email/logo-bottom.png') }}" alt="Pak Connections Logo" style="max-width: 120px;">
        </div>
    </div>
</body>

</html>
