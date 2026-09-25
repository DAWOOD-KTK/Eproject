<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'favoritable_type',
        'favoritable_id',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function farmer()
    {
        return $this->belongsTo(User::class, 'favoritable_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'favoritable_id');
    }
}
