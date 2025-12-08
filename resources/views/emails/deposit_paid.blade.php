<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Deposit Payment Received</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #ffffff; margin: 0; padding: 0;">
    <div style="max-width: 700px; margin: 0 auto; padding: 30px; border: 1px solid #e0e0e0;">

        @include('emails.partials.header')

        {{-- Email Content --}}
        <p style="font-size: 16px;">Dear
            {{ isset($bookedService->user) ? ' ' . $bookedService->user->full_name : 'Member' }}{{ isset($bookedService->user) && !empty($bookedService->user->membership_id) ? ' (' . $bookedService->user->membership_id . ')' : '' }},</p>

        <p style="font-size: 16px;">
            We’re pleased to confirm that we have successfully received your <strong>£100 deposit payment</strong> —
            thank you!
        </p>

        <p style="font-size: 16px;">
            Our team is now actively working behind the scenes to secure your requested bookings and begin coordinating
            all services you’ve selected in your Services Request Form.
        </p>

        <p style="font-size: 16px;"><strong>What Happens Next?</strong><br>
            Here’s what you can expect moving forward:</p>

        <ol style="font-size: 16px; padding-left: 20px;">
            <li>Your Concierge Manager is now arranging your itinerary with our trusted partners and vendors.</li>
            <li>We will soon send you a detailed service summary outlining the bookings we’ve made on your behalf.</li>
            <li>You’ll then receive your final invoice along with a secure payment link for the remaining balance.</li>
            <li>Once final payment is received, your bookings will be fully confirmed and ready for your arrival in
                Pakistan.</li>
        </ol>

        <p style="font-size: 16px;">
            Our goal is to deliver a smooth, secure, and completely personalised experience — so you can travel with
            confidence and comfort.
        </p>

        <p style="font-size: 16px;">
            Thank you for choosing <strong>Pak Connections</strong> — we’re honoured to be your support in Pakistan.
        </p>

        <p style="font-size: 16px;">
            Warm regards,<br>
            <strong>Naveed Khan, CEO</strong><br>
            <strong>Pak Connections</strong><br>
            <em>“Your Personal Assistant in Pakistan.”</em>
        </p>

        <hr style="margin: 30px 0;">

        @include('emails.partials.footer')

    </div>
</body>

</html>
