<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        "vendor_id",
        "package_id",
        "payment_status",
        "payment_reference",
        "starts_at",
        "ends_at",
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
