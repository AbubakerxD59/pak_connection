<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Verification Document Rejected</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #ffffff; margin: 0; padding: 0;">
    <div style="max-width: 700px; margin: 0 auto; padding: 30px; border: 1px solid #e0e0e0;">

        @include('emails.partials.header')

        {{-- Email Content --}}
        <p style="font-size: 16px;">Dear {{ $user->full_name }},</p>

        <p style="font-size: 16px;">
            Thank you for submitting your verification document to <strong>Pak Connections</strong>.
        </p>

        <p style="font-size: 16px;">
            Unfortunately, after careful review, we are unable to approve your submitted
            <strong>{{ ucfirst($document->document_type) }}</strong> document at this time.
        </p>

        <div
            style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px;">
            <p style="margin: 0; font-size: 16px; font-weight: bold; color: #856404;">
                <i>Reason for Rejection:</i>
            </p>
            <p style="margin: 10px 0 0 0; font-size: 16px; color: #856404;">
                {{ $adminNotes }}
            </p>
        </div>

        <p style="font-size: 16px;"><strong>What You Need to Do Next:</strong></p>

        <ol style="font-size: 16px; padding-left: 20px; line-height: 1.6;">
            <li>Please review the rejection reason above carefully.</li>
            <li>Prepare a new document that addresses the issues mentioned.</li>
            <li>Log in to your member portal at <a href="{{ route('frontend.home') }}"
                    style="color: #28a745; text-decoration: none;">{{ route('frontend.home') }}</a></li>
            <li>Click on "Order a Service" to upload your new verification document.</li>
        </ol>

        <p style="font-size: 16px;">
            <strong>Important:</strong> You will need to complete the verification process before you can order any
            services through our platform.
        </p>

        <p style="font-size: 16px;">
            If you have any questions or need clarification about the rejection reason, please don't hesitate to contact
            our support team at <a href="tel:+923205023407" style="color: #28a745; text-decoration: none;">
                {{ setting('support_phone') }}</a> or reply to this email.
        </p>

        <p style="font-size: 16px;">
            We appreciate your understanding and look forward to assisting you further.
        </p>

        <p style="font-size: 16px;">
            Warm regards,<br>
            <strong>Verification Team</strong><br>
            <strong>Pak Connections</strong><br>
            <em>"Your Personal Assistant in Pakistan."</em>
        </p>

        <hr style="margin: 30px 0;">

        @include('emails.partials.footer')

    </div>
</body>

</html>
