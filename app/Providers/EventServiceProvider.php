<?php

namespace App\Providers;

use App\Events\BookedServiceStatusUpdated;
use App\Listeners\SendDepositRequestedMail;
use App\Listeners\SendFullPaymentReceivedEmail;
use App\Listeners\SendInvoiceCreatedMail;
use App\Listeners\SendMemberArrivedEmail;
use App\Listeners\SendOrderCompletedEmail;
use App\Listeners\SendOrderInProgressMail;
use App\Listeners\SendPreArrivalEmail;
use App\Listeners\SendScheduleCreatedEmail;
use App\Listeners\SendDepositPaidMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // ✅ Your custom event and listeners
        BookedServiceStatusUpdated::class => [
            SendDepositRequestedMail::class,
            SendOrderInProgressMail::class,
            SendInvoiceCreatedMail::class,

            SendFullPaymentReceivedEmail::class,
            SendScheduleCreatedEmail::class,
            SendPreArrivalEmail::class,
            SendMemberArrivedEmail::class,
            SendOrderCompletedEmail::class,

            SendDepositPaidMail::class,

        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
