<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'code',
        'usage_limit',
        'used_times',
        'start_date',
        'end_date',
        'is_active',
        'value',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
