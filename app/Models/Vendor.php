<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Vendor extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        "name",
        "email",
        "password",
        "department_id",
        "package_id",
        "status",
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function currentSubscription()
    {
        return $this->hasOne(Subscription::class)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->latest();
    }
}
