<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Deposit Payment Link – Let’s Start Securing Your Bookings</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #ffffff; margin: 0; padding: 0;">
    <div style="max-width: 700px; margin: 0 auto; padding: 30px; border: 1px solid #e0e0e0;">

        @include('emails.partials.header')

        {{-- Email Content --}}
        <p style="font-size: 16px;">Dear  <strong>{{ $bookedService->user?->full_name ?? 'Member' }}{{ !empty($bookedService->user?->membership_id) ? ' MID#' . $bookedService->user->membership_id : '' }}</strong>,</p>

        <p style="font-size: 16px;">
            Thank you once again for submitting your Services Request Form and confirming the details with our Concierge Team.
            We’re now ready to begin securing your personalised bookings.
        </p>

        <p style="font-size: 16px;">
            To proceed, we kindly ask you to pay a <strong>£100 deposit</strong>, which allows us to begin making your reservations, liaising with our trusted partners, and locking in your requested services.
        </p>

        <p style="font-size: 16px; font-weight: bold;">Deposit Payment Link</p>

        <p style="font-size: 16px;">
            Please use the secure button below to make your deposit payment:
        </p>

        <p style="font-size: 16px;">
            <a href="{{ $bookedService->deposit_url ?? '#' }}" target="_blank"
               style="display: inline-block; padding: 12px 20px; background-color: #1a73e8; color: white; text-decoration: none; border-radius: 5px; margin: 15px 0;">
                Pay Deposit Now
            </a>
        </p>

        <p style="font-size: 16px;">
            Once payment is received, our team will:
        </p>
        <ul style="font-size: 16px; padding-left: 20px;">
            <li>Begin arranging all requested services</li>
            <li>Coordinate directly with our sub-contractors and concierge partners</li>
            <li>Prepare your detailed booking schedule and invoice</li>
        </ul>

        <p style="font-size: 16px; font-weight: bold; margin-top: 25px;">What Happens After Payment?</p>
        <ol style="font-size: 16px; padding-left: 20px;">
            <li>You’ll receive a summary of your itinerary and bookings.</li>
            <li>A final invoice and payment link will be issued.</li>
            <li>Upon full payment, your entire package will be confirmed and ready for your arrival.</li>
        </ol>

        <p style="font-size: 16px;">
            If you have any questions or wish to make changes, we’re just a message away.
        </p>

        <p style="font-size: 16px;">
            We’re excited to start working our magic on your journey. Thank you for trusting <strong>Pak Connections</strong> — your very own personal assistant in Pakistan.
        </p>

        <p style="font-size: 16px;">Warm regards,<br>
            <strong>Naveed Khan</strong><br>
            CEO, Pak Connections
        </p>

        <hr style="margin: 30px 0;">

        
        @include('emails.partials.footer')

        
    </div>
</body>

</html>
