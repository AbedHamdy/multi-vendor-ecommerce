<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "package_id",
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
