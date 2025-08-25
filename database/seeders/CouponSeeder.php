<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;
use Carbon\Carbon;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['welcome', 'loyalty', 'event', 'general'];
        $discountTypes = ['fixed', 'percent'];

        foreach ($types as $type)
        {
            Coupon::create([
                'code' => strtoupper($type) . rand(100, 999),
                'type' => $type,
                'discount_type' => $discountTypes[array_rand($discountTypes)],
                'value' => $type === 'welcome' ? 50 : 20,
                'usage_limit' => 100,
                'used_times' => 0,
                'min_order_amount' => 200,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now()->addMonths(2),
                'is_active' => true,
            ]);
        }
    }
}
