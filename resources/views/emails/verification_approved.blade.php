<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Verification Approved</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #ffffff; margin: 0; padding: 0;">
    <div style="max-width: 700px; margin: 0 auto; padding: 30px; border: 1px solid #e0e0e0;">

        @include('emails.partials.header')

        {{-- Email Content --}}
        <p style="font-size: 16px;">Dear {{ $user->full_name }}{{ !empty($user->membership_id) ? ' MID#' . $user->membership_id : '' }},</p>

        <p style="font-size: 16px;">
            Great news! We're pleased to inform you that your verification document has been successfully
            <strong>approved</strong>.
        </p>

        <div
            style="background-color: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin: 20px 0; border-radius: 4px;">
            <p style="margin: 0; font-size: 18px; font-weight: bold; color: #155724;">
                ✓ Your Account is Now Verified
            </p>
            <p style="margin: 10px 0 0 0; font-size: 16px; color: #155724;">
                You can now access all our services and start booking with Pak Connections.
            </p>
        </div>

        <p style="font-size: 16px;"><strong>What You Can Do Now:</strong></p>

        <ol style="font-size: 16px; padding-left: 20px; line-height: 1.6;">
            <li>Log in to your member portal at <a href="{{ route('frontend.home') }}"
                    style="color: #28a745; text-decoration: none;">{{ route('frontend.home') }}</a></li>
            <li>Click on <strong>"Order a Service"</strong> to start booking your services</li>
            <li>Browse through our comprehensive range of services tailored for your needs</li>
            <li>Submit your service requests and let us handle the rest</li>
        </ol>

        <p style="font-size: 16px;">
            As a verified member, you now have full access to:
        </p>

        <ul style="font-size: 16px; padding-left: 20px; line-height: 1.6;">
            <li>Priority service bookings</li>
            <li>Dedicated concierge support</li>
            <li>Customized travel and accommodation arrangements</li>
            <li>24/7 assistance during your stay in Pakistan</li>
        </ul>

        <p style="font-size: 16px;">
            We're excited to support you on your journey! Our team is ready to make your experience in Pakistan smooth,
            secure, and memorable.
        </p>

        <div
            style="background-color: #f8f9fa; padding: 15px; margin: 20px 0; border-radius: 4px; border: 1px solid #dee2e6;">
            <p style="margin: 0; font-size: 16px; color: #495057;">
                <strong>Need Help?</strong><br>
                If you have any questions or need assistance, our support team is here for you:<br>
                📞 <a href="tel:{{ setting('support_phone') }}"
                    style="color: #28a745; text-decoration: none;">{{ setting('support_phone') }}</a><br>
                ✉️ Reply to this email anytime
            </p>
        </div>

        <p style="font-size: 16px;">
            Thank you for choosing <strong>Pak Connections</strong> — we look forward to serving you!
        </p>

        <p style="font-size: 16px;">
            Warm regards,<br>
            <strong>Naveed Khan, CEO</strong><br>
            <strong>Pak Connections</strong><br>
            <em>"Your Personal Assistant in Pakistan."</em>
        </p>

        <hr style="margin: 30px 0;">

        @include('emails.partials.footer')

    </div>
</body>

</html>
