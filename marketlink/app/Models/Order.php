<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'farmer_id',
        'market_id',
        'total_amount',
        'order_status',
        'pickup_date',
        'pickup_time_slot',
        'cutoff_time',
        'customer_notes',
        'farmer_notes',
        'cancellation_reason',
        'cancelled_by',
    ];

    protected function casts(): array
    {
        return [
            'pickup_date' => 'date',
            'cutoff_time' => 'datetime',
            'total_amount' => 'decimal:2',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function market()
    {
        return $this->belongsTo(Market::class, 'market_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'order_id');
    }

    public function canBeModifiedOrCancelled(): bool
    {
        if (in_array($this->order_status, ['completed', 'cancelled'])) {
            return false;
        }

        if ($this->cutoff_time && Carbon::now()->greaterThanOrEqualTo($this->cutoff_time)) {
            return false;
        }

        return true;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->order_status) {
            'placed' => 'bg-warning text-dark',
            'accepted' => 'bg-info text-dark',
            'ready_for_pickup' => 'bg-primary text-white',
            'completed' => 'bg-success text-white',
            'cancelled' => 'bg-danger text-white',
            default => 'bg-secondary text-white',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->order_status) {
            'placed' => 'Order Placed',
            'accepted' => 'Accepted by Farmer',
            'ready_for_pickup' => 'Ready for Pickup',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->order_status),
        };
    }
}
