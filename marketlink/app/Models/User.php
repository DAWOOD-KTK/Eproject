<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'contact_person',
        'username',
        'email',
        'password',
        'role',
        'status',
        'phone',
        'address',
        'stall_name',
        'market_id',
        'operating_days',
        'pickup_windows',
        'cutoff_hours',
        'latitude',
        'longitude',
        'bio',
        'avatar',
        'preferred_market_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'latitude' => 'float',
            'longitude' => 'float',
            'cutoff_hours' => 'integer',
        ];
    }

    // Role helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isFarmer(): bool
    {
        return $this->role === 'farmer';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending_approval';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    // Relationships
    public function market()
    {
        return $this->belongsTo(Market::class, 'market_id');
    }

    public function preferredMarket()
    {
        return $this->belongsTo(Market::class, 'preferred_market_id');
    }

    public function farmerProducts()
    {
        return $this->hasMany(Product::class, 'farmer_id');
    }

    public function farmerOrders()
    {
        return $this->hasMany(Order::class, 'farmer_id');
    }

    public function customerOrders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function farmerReviews()
    {
        return $this->hasMany(Review::class, 'farmer_id');
    }

    public function customerReviews()
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'customer_id');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'user_id');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'generated_by');
    }

    public function averageRating(): float
    {
        return round($this->farmerReviews()->avg('rating') ?? 5.0, 1);
    }
}
