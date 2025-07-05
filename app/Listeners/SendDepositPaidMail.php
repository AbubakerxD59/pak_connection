<?php

namespace App\Listeners;

use App\Events\BookedServiceStatusUpdated;
use App\Mail\DepositPaidMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendDepositPaidMail
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
        if ($event->bookedService->status == 3) {
            Mail::to($event->bookedService->user->email)
                ->send(new DepositPaidMail($event->bookedService));
        }
    }
}
