<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Final Invoice – Complete Your Payment to Confirm Your Bookings</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #ffffff; margin: 0; padding: 0;">
    <div style="max-width: 700px; margin: 0 auto; padding: 30px; border: 1px solid #e0e0e0;">

        @include('emails.partials.header')
        
        {{-- Email Content --}}
       <p style="font-size: 16px;">Dear  <strong>{{ $bookedService->user?->full_name ?? 'Member' }}</strong>,</p>

        <p style="font-size: 16px;">
            We’re excited to let you know that all your requested services have now been prepared, and your itinerary is ready to go!
        </p>

        <p style="font-size: 16px;">
            Please find your <strong>Final Invoice</strong> attached below, reflecting the full cost of your selected services. This invoice includes all arrangements discussed with your Concierge Manager, and the <strong>£100 deposit</strong> you’ve already paid has been deducted from the total.
        </p>

        <p style="font-size: 16px; font-weight: bold;">Action Required: Complete Your Final Payment</p>

        <p style="font-size: 16px;">
            To fully confirm and activate your bookings, please use the secure payment link(s) below:
        </p>

        @if(!empty($invoiceLink))
        <p style="font-size: 16px; font-weight: bold;">
            <a href="{{ $invoiceLink }}" target="_blank" style="color: #1a73e8; text-decoration: none;">View Final Invoice</a>
        </p>
        @endif

        @if(!empty($paymentLink))
        <p style="font-size: 16px; font-weight: bold;">
            <a href="{{ $paymentLink }}" target="_blank" style="color: #1a73e8; text-decoration: none;">Pay Final Balance</a>
        </p>
        @endif

        <p style="font-size: 16px;">
            Once your payment is received:
        </p>
        <ul style="font-size: 16px; padding-left: 20px;">
            <li>All bookings will be locked in and confirmed</li>
            <li>You will receive an <strong>Itinerary & Schedule</strong> Confirmation with full details and contact information</li>
            <li>Our local teams will be on standby for your arrival in Pakistan</li>
        </ul>

        <p style="font-size: 16px;">
            We’re here to assist you every step of the way.
        </p>

        <p style="font-size: 16px;">
            Thank you for choosing <strong>Pak Connections</strong>. We look forward to welcoming you soon and ensuring your visit is seamless, stress-free, and unforgettable.
        </p>

        <p style="font-size: 16px;">
            Warm regards,<br>
            <strong>Naveed Khan</strong><br>
            <strong>CEO, Pak Connections</strong>
        </p>

        <p style="font-size: 16px; font-style: italic; margin-top: 10px;">
            "Let us handle the details, so you can enjoy the experience."
        </p>

        <hr style="margin: 30px 0;">

        
        @include('emails.partials.footer')

    </div>
</body>

</html>
