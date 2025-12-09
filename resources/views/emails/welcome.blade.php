<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Payment Received, Welcome to Pak Connections – Your Luxury Experience Starts Now </title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #ffffff; margin: 0; padding: 0;">
    <div style="max-width: 700px; margin: 0 auto; padding: 30px; border: 1px solid #e0e0e0;">

        @include('emails.partials.header')

        <p style="font-size: 16px;">Dear
            <strong>
                {{ $user->full_name ?? 'Member' }}{{ !empty($user->membership_id) ? ' MID#' . $user->membership_id : '' }}
            </strong>
            ,
        </p>

        <p style="font-size: 16px;">
            We’re delighted to welcome you to <strong>Pak Connections</strong> — your very own <em><strong>Personal
                    Assistant</strong></em> for premium, stress-free
            travel and lifestyle services in Pakistan.
            <br>
            <strong>We’re pleased to confirm that your membership payment has been successfully received.</strong> You
            are now an official Pak Connections Member, and your journey to a smoother, safer, and more enjoyable stay
            in Pakistan begins here.
        </p>

        <p style="font-size: 16px;"><strong>What Happens Next?</strong></p>
        <p style="font-size: 16px;">To get started with our services, simply follow the steps below:</p>
        <ol style="font-size: 16px; padding-left: 20px;">
            <li>
                <strong>Ready to travel?</strong>Great — we’re ready for you.
            </li>
            <li>
                <strong>Submit your Service Request Form.</strong>Use the link below to tell us what you’ll need during
                your visit — whether it’s airport pickups, private tours, accommodation, medical assistance, or
                something more bespoke.
                <br>
                <a href="{{ route('frontend.member.home') }}" target="_blank">
                    <span>{{ route('frontend.member.home') }}</span>
                </a>
            </li>
            <li>
                <strong>We’ll contact you.</strong> . One of our Concierge Managers will call to confirm your
                requirements and finalise your itinerary.
            </li>
            <li>
                <strong>We start working our magic.</strong>Our teams will arrange all services and bookings behind the
                scenes.
            </li>
            <li>
                <strong>Receive your final invoice and payment link.</strong>Once full payment is received, your trip is
                ready.
            </li>
            <li>
                <strong>Arrive in Pakistan stress-free,</strong> and enjoy your journey while we handle everything else.
            </li>
        </ol>

        <p style="font-size: 16px;">
            <strong>
                **** Please note: The services available to you depend on the type of membership you have purchased. For
                a complete list of what’s included in your membership tier, refer to your emailed invoice and the
                membership summary attached.
            </strong>
        </p>

        <p style="font-size: 16px;">
            Once again, thank you for choosing Pak Connections. We
            look forward to providing you with a luxury, fully
            supported experience that gives you peace of mind from the
            moment you land in Pakistan.
        </p>

        <p style="font-size: 16px;">
            Warm regards,<br>
            <strong>Naveed Khan</strong>
            <br>
            CEO, Pak Connections
            <br>
            <em>“Your very own personal assistant in Pakistan”</em>
        </p>

        <hr style="margin: 30px 0;">


        @include('emails.partials.footer')

    </div>
</body>

</html>
