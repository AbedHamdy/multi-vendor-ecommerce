<?php

namespace App\Console\Commands;

use App\Models\Coupon;
use App\Models\CouponUser;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\CouponMail;

class SendCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupons:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send coupons to users based on scenario';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        $welcomeCoupons = Coupon::where('type', 'welcome')
            ->where("is_active" , true)
            ->get();

        $newUsers = User::whereDate('created_at', $now->toDateString())->get();
        foreach ($welcomeCoupons as $coupon)
        {
            foreach ($newUsers as $user)
            {
                $alreadySent = CouponUser::where('coupon_id', $coupon->id)
                    ->where('user_id', $user->id)
                    ->exists();


                if (!$alreadySent)
                {
                    Mail::to($user->email)->queue(new CouponMail($coupon));

                    CouponUser::create([
                        'coupon_id' => $coupon->id,
                        'user_id' => $user->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        $loyaltyCoupons = Coupon::where('type', 'loyalty')
            ->where("is_active" , true)
            ->get();

        $loyalUsers = User::has('orders', '>=', 10)->get();
        foreach ($loyaltyCoupons as $coupon)
        {
            foreach ($loyalUsers as $user)
            {
                $alreadySent = CouponUser::where('coupon_id', $coupon->id)
                    ->where('user_id', $user->id)
                    ->exists();


                if (!$alreadySent)
                {
                    Mail::to($user->email)->queue(new CouponMail($coupon));

                    CouponUser::create([
                        'coupon_id' => $coupon->id,
                        'user_id' => $user->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // $eventCoupons = Coupon::where('type', 'event')
        //     ->where("is_active" , true)
        //     ->get();
        // foreach ($eventCoupons as $coupon)
        // {
        //     $users = User::whereHas('orders', function($q) use ($coupon) {
        //         $q->whereBetween('created_at', [$coupon->start_date, $coupon->end_date]);
        //     })->get();

        //     foreach ($users as $user)
        //     {
        //         Mail::to($user->email)->queue(new CouponMail($coupon));
        //     }
        // }

        $this->info('Coupons sent successfully!');
    }
}
