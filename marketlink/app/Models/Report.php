<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'generated_by',
        'report_type',
        'parameters',
        'summary_data',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'parameters' => 'array',
            'summary_data' => 'array',
            'generated_at' => 'datetime',
        ];
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
