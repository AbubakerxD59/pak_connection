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
        <p style="font-size: 16px;">Dear <strong>{{ $bookedService->user?->full_name ?? 'Member' }}</strong>,</p>

        <p style="font-size: 16px;">
            We’re excited to let you know that all your requested services have now been prepared, and your itinerary is
            ready to go!
        </p>

        <p style="font-size: 16px;">
            Please find your <strong>Final Invoice</strong> attached below, reflecting the full cost of your selected
            services. This invoice includes all arrangements discussed with your Concierge Manager, and the <strong>£100
                deposit</strong> you’ve already paid has been deducted from the total.
        </p>

        <p style="font-size: 16px; font-weight: bold;">Action Required: Complete Your Final Payment</p>

        <p style="font-size: 16px;">
            To fully confirm and activate your bookings, please use the secure payment link(s) below:
        </p>

        <table style="width: 100%; border-collapse: collapse; font-size: 16px; margin: 20px 0;">
            <tr>
                <th style="text-align: left; padding: 10px; border-bottom: 1px solid #ccc;">Service Name</th>
                <td style="padding: 10px; border-bottom: 1px solid #ccc;">{{ $bookedService->service_name }}</td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 10px; border-bottom: 1px solid #ccc;">Total Amount</th>
                <td style="padding: 10px; border-bottom: 1px solid #ccc;">
                    £{{ number_format($bookedService->total_amount, 2) }}</td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 10px; border-bottom: 1px solid #ccc;">Discount</th>
                <td style="padding: 10px; border-bottom: 1px solid #ccc;">
                    £{{ number_format($bookedService->discount_amount, 2) }}</td>
            </tr>
            <tr>
                <th style="text-align: left; padding: 10px; border-bottom: 1px solid #ccc;">Payable Amount</th>
                <td style="padding: 10px; border-bottom: 1px solid #ccc;">
                    <strong>£{{ number_format($bookedService->payable_amount, 2) }}</strong>
                </td>
            </tr>
        </table>


        {{-- @if (!empty($bookedService->invoice_url)) --}}
        <div style="text-align: center;">
            <a href="{{ url(asset('/') . $bookedService->invoice_pdf) }}" target="_blank"
                style="display: inline-block; padding: 12px 20px; background-color: green; color: white; text-decoration: none; border-radius: 5px; margin: 15px 0; font-size: 16px;">
                View Invoice
            </a>

            <a href="{{ $bookedService->invoice_url ?? '#' }}" target="_blank"
                style="display: inline-block; padding: 12px 20px; background-color: #1a73e8; color: white; text-decoration: none; border-radius: 5px; margin: 15px 0; font-size: 16px;">
                Pay Invoice
            </a>
        </div>
        {{-- @endif --}}


        <p style="font-size: 16px;">
            Once your payment is received:
        </p>
        <ul style="font-size: 16px; padding-left: 20px;">
            <li>Your booking will be locked in and confirmed</li>
            <li>You will receive a <strong>Booking Confirmation</strong> with full details and contact
                information</li>
            <li>Our local teams will be on standby for your arrival in Pakistan</li>
        </ul>

        <p style="font-size: 16px;">
            We’re here to assist you every step of the way.
        </p>

        <p style="font-size: 16px;">
            Thank you for choosing <strong>Pak Connections</strong>. We look forward to welcoming you soon and ensuring
            your visit is seamless, stress-free, and unforgettable.
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
