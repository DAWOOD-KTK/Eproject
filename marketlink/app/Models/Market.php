<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use HasFactory;

    protected $fillable = [
        'market_name',
        'address',
        'operating_days',
        'timings',
        'latitude',
        'longitude',
    ];
}