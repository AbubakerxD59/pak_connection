<?php

namespace App\Listeners;

use App\Events\BookServicePdfUploaded;
use App\Mail\BookServicePdfEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class SendBookServicePdfEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookServicePdfUploaded $event): void
    {
        $user = $event->bookedServicePdf->user ?? null;

        if ($user && !empty($user->email)) {
            Mail::to($user->email)->send(new BookServicePdfEmail($event->bookedServicePdf));
        } else {
            Log::warning('PDF email not sent: user or email not found for BookedServicePdf ID ' . $event->bookedServicePdf->id);
        }
    }
}
