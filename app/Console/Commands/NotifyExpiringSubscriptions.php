<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Jobs\SendSubscriptionExpiringEmail;
use Carbon\Carbon;

class NotifyExpiringSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:notify-expiring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify vendors about expiring subscriptions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = Carbon::now()->addDays(3);
        $subscriptions = Subscription::whereDate('ends_at', $date)->get();
        foreach ($subscriptions as $subscription)
        {
            SendSubscriptionExpiringEmail::dispatch($subscription);
        }

        $this->info("Notifications dispatched for " . $subscriptions->count() . " subscriptions.");
    }
}
