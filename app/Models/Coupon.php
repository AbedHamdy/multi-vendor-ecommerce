<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        "code",
        "type",
        "discount_type",
        "value",
        "usage_limit",
        "used_times",
        "min_order_amount",
        "start_date",
        "end_date",
        "is_active",
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
