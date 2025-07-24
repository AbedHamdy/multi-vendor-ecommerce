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
}
