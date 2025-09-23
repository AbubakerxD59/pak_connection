<?php

namespace App\Listeners;

use App\Events\BookedServiceStatusUpdated;
use App\Mail\AdminPreArrivalMail;
use App\Mail\PreArrivalMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendPreArrivalEmail
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
                ->send(new PreArrivalMail($event->bookedService));

            $email = env("POCC_TEAM");
            Mail::to($email)
                ->send(new AdminPreArrivalMail($event->bookedService));
        }
    }
}
