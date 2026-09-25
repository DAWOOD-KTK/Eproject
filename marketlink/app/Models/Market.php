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
        'city',
        'operating_days',
        'timings',
        'latitude',
        'longitude',
        'map_provider',
        'image',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function farmers()
    {
        return $this->hasMany(User::class, 'market_id')->where('role', 'farmer');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'market_id');
    }
}