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

    public $bookService;
    /**
     * Create a new message instance.
     */
    public function __construct($bookService)
    {
        $this->bookService = $bookService;
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
                "name" => $this->bookService->user->name,
                "invoice_url" => $this->bookService->invoice_url,
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
