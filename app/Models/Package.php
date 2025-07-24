<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "status",
        "price",
    ];

    public function features()
    {
        return $this->hasMany(Feature::class);
    }

    public function vendors()
    {
        return $this->hasMany(Vendor::class);
    }
}
