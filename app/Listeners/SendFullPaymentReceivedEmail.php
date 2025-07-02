<?php

namespace App\Listeners;

use App\Events\BookedServiceStatusUpdated;
use App\Mail\AdminFullPaymentReceivedMail;
use App\Mail\FullPaymentReceivedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendFullPaymentReceivedEmail
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
    public function handle(BookedServiceStatusUpdated $event): void
    {
        if ($event->bookedService->status == 8) {
            Mail::to($event->bookedService->user->email)
                ->send(new FullPaymentReceivedMail($event->bookedService));

            
            // email : operations@pakconnections.co.uk
            // Mail::to('')
            //     ->send(new AdminFullPaymentReceivedMail($event->bookedService));
        }
    }
}
