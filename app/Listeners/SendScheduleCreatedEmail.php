<?php

namespace App\Listeners;

use App\Events\BookedServiceStatusUpdated;
use App\Mail\BookingConfirmedMail;
use Illuminate\Support\Facades\Mail;

class SendScheduleCreatedEmail
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
        if ($event->bookedService->status == 7) {
            Mail::to($event->bookedService->user->email)
                ->send(new BookingConfirmedMail($event->bookedService));
        }
    }
}
