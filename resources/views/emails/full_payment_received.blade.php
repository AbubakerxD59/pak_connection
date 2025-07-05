<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Final Payment Confirmation</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #ffffff; margin: 0; padding: 0;">
    <div style="max-width: 700px; margin: 0 auto; padding: 30px; border: 1px solid #e0e0e0;">

        @include('emails.partials.header')

        <!-- Email Content -->
      <p style="font-size: 16px;">Dear  <strong>{{ $bookedService->user?->full_name ?? 'Member' }}</strong>,</p>

        <p style="font-size: 16px;">We’re pleased to confirm that we have received your final payment in full — thank
            you!</p>

        <p style="font-size: 16px;">
            Your entire booking is now fully confirmed, and our team has activated all services you requested. From this
            point forward,
            your journey is in the hands of our expert concierge teams who are fully briefed and ready to support you
            every step of the way.
        </p>

        <!-- Invoice Link -->
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

        @include('emails.partials.footer')

    </div>
</body>

</html>
