<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_id',
        'category_id',
        'category',
        'name',
        'description',
        'price',
        'unit',
        'stock_quantity',
        'image',
        'is_available',
        'is_weekly_template',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'is_available' => 'boolean',
            'is_weekly_template' => 'boolean',
        ];
    }

    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function categoryModel()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function averageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 5.0, 1);
    }

    public function reviewCount(): int
    {
        return $this->reviews()->count();
    }
}
