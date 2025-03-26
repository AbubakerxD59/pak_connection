<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\WebhookClient\Models\WebhookCall;

class StripWeebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var \Spatie\WebhookClient\Models\WebhookCall */
    public $webhookCall;
    private $user;
    private $subscription;

    public function __construct(WebhookCall $webhookCall, User $user, Subscription $subscription)
    {
        $this->webhookCall = $webhookCall;
        $this->user = $user;
        $this->subscription = $subscription;
    }

    public function handle()
    {
        $payload = $this->webhookCall->payload['data']['object'];
        $user = $this->user->where('stripe_id', $payload['customer'])->first();
        if ($user) {
            $this->subscription->create([
                'user_id' => $user->id,
                'type' => $payload['payment_method_details']['type'],
                'stripe_id' => $payload['id'],
                'stripe_status' => $payload['status'],
                'stripe_price' => $payload['amount'],
                'quantity' => 1,
                'trial_ends_at' => 0,
                'ends_at' => 0
            ]);
        }
    }
}
