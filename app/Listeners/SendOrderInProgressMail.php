<?php

namespace App\Listeners;

use App\Events\BookedServiceStatusUpdated;
use App\Mail\OrderInProgressMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendOrderInProgressMail
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
        if ($event->bookedService->status == 4) {
            $email = env("POCC_TEAM");
            Mail::to($email)
                ->send(new OrderInProgressMail($event->bookedService));
        }
    }
}
