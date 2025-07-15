<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pak Connections Services Confirmation</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #ffffff; margin: 0; padding: 0;">
    <div style="max-width: 700px; margin: 0 auto; padding: 30px; border: 1px solid #e0e0e0;">

        @include('emails.partials.header')

        <p style="font-size: 16px;">Dear  <strong>{{ $name ?? 'Member' }}</strong>,</p>

        <p style="font-size: 16px;">
            Thank you for submitting your <strong>Pak Connections Services Request Order Form</strong> — we’re delighted to begin planning your personalised experience in Pakistan.
            Our Concierge Team has received your form and is now reviewing your requirements. Whether you’ve requested airport meet and greet, transportation, accommodation, medical support, or bespoke tours, rest assured that your requests are being handled with priority and care.
        </p>

        <p style="font-size: 16px;"><strong>What Happens Next?</strong></p>

        <ol style="font-size: 16px; padding-left: 20px;">
            <li><strong>A Concierge Manager will call you shortly</strong> to confirm the details of your request and clarify any special requirements.</li>
            <li>Once confirmed, you’ll receive a booking summary and a £100 deposit payment link to begin securing your arrangements and lock in your reservations.</li>
            <li>After deposit payment, we will start coordinating with our teams and brand partners to arrange your bookings.</li>
            <li>You’ll then receive your final invoice with a full payment link.</li>
            <li>Upon full payment, your services will be confirmed.</li>
        </ol>

        <p style="font-size: 16px;">
            Our team is committed to providing a seamless experience, so you can travel worry-free and focus on enjoying your time in Pakistan.
        </p>

        <p style="font-size: 16px;">
            Thank you for choosing Pak Connections. We look forward to delivering a first-class concierge experience tailored entirely to your needs.
        </p>

        <p style="font-size: 16px;">
            Warm regards,<br>
            <strong>Naveed Khan</strong><br>
            CEO, Pak Connections<br>
            <em>“Your very own personal assistant in Pakistan”</em>
        </p>

        <hr style="margin: 30px 0;">

       
        @include('emails.partials.footer')

    </div>
</body>
</html>
