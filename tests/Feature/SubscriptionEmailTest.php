<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionExpiring;
use App\Models\Subscription;

class SubscriptionEmailTest extends TestCase
{
    /** @test */
    public function it_sends_subscription_email()
    {
        Mail::fake();

        $subscription = Subscription::first();

        Mail::to('test@example.com')->send(new SubscriptionExpiring($subscription));

        Mail::assertSent(SubscriptionExpiring::class);
    }
}
