<?php

namespace App\Listeners;

use App\Events\BookedServiceStatusUpdated;
use App\Mail\OrderCompletedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderCompletedEmail
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
        if ($event->bookedService->status == 9) {
            Mail::to($event->bookedService->user->email)
                ->send(new OrderCompletedMail($event->bookedService));
        }
    }
}
