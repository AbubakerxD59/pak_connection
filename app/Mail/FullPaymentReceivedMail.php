<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FullPaymentReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $bookedService;
    /**
     * Create a new message instance.
     */
    public function __construct($bookedService)
    {
        $this->bookedService = $bookedService;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Final Payment Received – Your Bookings Are Now Fully Confirmed ',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.full_payment_received',
            with: [
                "name" => $this->bookedService->user->full_name,
                "invoice_url" => $this->bookedService->invoice_url,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
