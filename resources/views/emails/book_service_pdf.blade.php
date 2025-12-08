<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Deposit Payment Received</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #ffffff; margin: 0; padding: 0;">
    <div style="max-width: 700px; margin: 0 auto; padding: 30px; border: 1px solid #e0e0e0;">

        @include('emails.partials.header')

         <p style="font-size: 16px;">Dear
            {{ isset($bookedServicePdf->user) ? ' ' . $bookedServicePdf->user->full_name : 'Member' }}{{ isset($bookedServicePdf->user) && !empty($bookedServicePdf->user->membership_id) ? ' (' . $bookedServicePdf->user->membership_id . ')' : '' }},</p>


        {{-- Email Content --}}
        

        <p style="font-size: 16px;">
            {{ $bookedServicePdf->text }}
        </p>

         <p style="font-size: 16px;">
            <a href="{{ $bookedServicePdf->file ?? '#' }}" target="_blank"
               style="display: inline-block; padding: 12px 20px; background-color: #1a73e8; color: white; text-decoration: none; border-radius: 5px; margin: 15px 0;">
                See Book Service PDF
            </a>
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
