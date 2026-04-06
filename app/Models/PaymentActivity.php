<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'source',
        'type',
        'status',
        'message',
        'payload',
        'happened_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'happened_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
