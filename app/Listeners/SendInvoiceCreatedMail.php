<?php

namespace App\Listeners;

use App\Events\BookedServiceStatusUpdated;
use App\Mail\InvoiceCreatedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
// use Mail;
use Illuminate\Support\Facades\Mail;

class SendInvoiceCreatedMail
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
        if ($event->bookedService->status == 5) {
            Mail::to($event->bookedService->user->email)
                ->send(new InvoiceCreatedMail($event->bookedService));
        }
    }
}
