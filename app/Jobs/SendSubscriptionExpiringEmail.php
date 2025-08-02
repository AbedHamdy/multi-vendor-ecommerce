<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Mail\SubscriptionExpiring;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendSubscriptionExpiringEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $subscription;

    /**
     * Create a new job instance.
     */
    public function __construct($subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $vendor = $this->subscription->vendor;

        if ($vendor && $vendor->email)
        {
            Mail::to($vendor->email)->send(new SubscriptionExpiring($this->subscription));
        }
    }
}
